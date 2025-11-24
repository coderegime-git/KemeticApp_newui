<?php

namespace App\PaymentChannels\Drivers\Stripe;

use App\Models\Order;
// use App\Models\User;
use App\User;
use App\Models\Region;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionRenewal;
use Exception;
use App\Models\OrderItem;
use App\Models\Subscribe;

class Channel extends BasePaymentChannel implements IChannel
{
    protected $currency;
    protected $test_mode;
    protected $api_key;
    protected $api_secret;
    protected $order_session_key;


    protected array $credentialItems = [
        'api_key',
        'api_secret',
    ];

    /**
     * Channel constructor.
     * @param PaymentChannel $paymentChannel
     */
    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->currency = currency();
        $this->order_session_key = 'strip.payments.order_id';
        $this->setCredentialItems($paymentChannel);

        if (empty($this->api_secret)) {
            $this->api_secret = env('STRIPE_SECRET');
        }
        if (empty($this->api_key)) {
            $this->api_key = env('STRIPE_KEY');
        }
    }

    public function paymentRequest(Order $order)
    {
       
        //$price = round($this->makeAmountByCurrency($order->total_amount, $this->currency),2);
        $priceFloat = round($this->makeAmountByCurrency($order->total_amount, $this->currency), 2);
        $priceCents = (int) round($priceFloat * 100);

        $generalSettings = getGeneralSettings();
        $currency = currency();
        $currency = $currency == 'USD' ? 'EUR' : $currency;   

        Stripe::setApikey(env('STRIPE_SECRET'));
        $successUrl = (session()->get('mobileHeader') == 1)
            ? 'https://kemetic.app/paymentSuccess'
            : $this->makeCallbackUrl('success');
        
        $checkoutData = [
            //'payment_method_types' => ['card', 'bancontact', 'ideal', 'p24', 'sofort', 'klarna', 'giropay', 'eps'],
            'payment_method_types' => ['ideal','card','p24', 'klarna', 'giropay', 'eps','bancontact'],
            'mode' => 'payment',
            'billing_address_collection' => 'required',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $currency,
                        //'unit_amount_decimal' => $price * 100,
                        'unit_amount_decimal' => $priceCents,
                        'product_data' => [
                            'name' => $generalSettings['site_name'] . ' payment',
                        ],
                    ],
                    'quantity' => 1,
                ]
            ],
            'metadata' => [
                'order_id'        => $order->id,
            ],
            'success_url' => $successUrl,
            // 'cancel_url' => $cancelUrl,
        ];

        // Add cancel_url only if mobileHeader != 1
        if (session()->get('mobileHeader') != 1) {
            $checkoutData['cancel_url'] = $this->makeCallbackUrl('cancel');
        }
        
        //dd('order1');
        // Create the checkout session
        // $checkout = Session::create($checkoutData);
        //$checkout = \Stripe\Checkout\Session::create($checkoutData);
        //dd('order1');
        // print_r($checkout);die;    
        /*$order->update([
            'reference_id' => $checkout->id,
        ]);*/
        //dd('order2');

        try {
            $checkout = Session::create($checkoutData);
        } catch (\Throwable $e) {
            Log::error('Stripe Checkout create failed: '.$e->getMessage(), [
                'order_id' => $order->id,
                'checkoutData' => $checkoutData
            ]);
            throw $e; // allow upstream to catch and show friendly toast
        }
        //dd('order2');
        session()->put($this->order_session_key, $order->id);

        if (session()->get('mobileHeader') == 1) {
            return apiResponse2(1, 'retrieved', 'Payment Url', ['url' => $checkout->url, 'session_id' => $checkout->id, 'order_id' => $order->id]);
        }

        $Html = '<script src="https://js.stripe.com/v3/"></script>';
        $Html .= '<script type="text/javascript">let stripe = Stripe("' . env('STRIPE_KEY') . '");';
        $Html .= 'stripe.redirectToCheckout({ sessionId: "' . $checkout->id . '" }); </script>';

        echo $Html;
    }
    
     public function recurringPaymentRequest(Order $order)
    {
        
        $orderItems = OrderItem::where('order_id', $order->id)->first();
        $subscribe = Subscribe::where('id', $orderItems->subscribe_id)->first();

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $successUrl = (session()->get('mobileHeader') == 1)
                ? 'https://kemetic.app/paymentSuccess'
                : $this->makeRecurringCallbackUrl('success');
                
            $user = User::findOrFail($order->user_id);
           
            // Ensure user has a Stripe customer ID
            if (!$user->stripe_customer_id) {
                $state = Region::where('id', $user->district_id)->where('type', 'province')->first();
                $city = Region::where('id', $user->city_id)->where('type', 'city')->first();
                $customer = Customer::create([
                    'email'  => $user->email,
                    'name'   => $user->full_name,
                    'address' => [
                        'line1'       => $user->address ?? '6th Floor, Indore',
                        'line2'       => $user->address ?? '1st Floor, Indore',
                        'city'        => $city->title ?? 'Indore',
                        'state'       => $state->title ?? 'Madhya Pradesh',
                        'postal_code' => $user->zip_code ?? '452001',
                        'country'     => 'IN',
                    ],
                ]);
                $user->stripe_customer_id = $customer->id;
                $user->save();
            } else {
                $customer = Customer::retrieve($user->stripe_customer_id);
            }

            if($subscribe->usable_count == '1'){

                $checkoutData = [
                    'payment_method_types' => ['card', 'bancontact', 'ideal', 'klarna'],
                    'mode' => 'payment', // ✅ one-off payment mode
                    'customer' => $customer->id,
                    'billing_address_collection' => 'required',
                    'line_items' => [[
                        // Option 1: Use a pre-defined Price ID (for one-time product)
                        'price' => $subscribe->price_id, // must be a one-time price created in Stripe Dashboard
                        'quantity' => 1,
                    ]],
                    'metadata' => [
                        'customer_name'  => $user->name,
                        'customer_email' => $user->email,
                        'order_id'       => $order->id,
                    ],
                    'success_url' => $successUrl,
                    //'cancel_url' => $cancelUrl ?? route('payment.cancel'),
                ];
            }
            else
            {
                $checkoutData = [
                //'payment_method_types' => ['card', 'bancontact', 'ideal', 'p24', 'sofort', 'klarna', 'giropay', 'eps'],
                'payment_method_types' => ['card','bancontact', 'ideal', 'klarna'],
                'mode'                 => 'subscription',
                'customer'             => $customer->id,
                'billing_address_collection' => 'required',
                'line_items' => [[
                    'price'    => $subscribe->price_id, // ✅ Use a test price with a 1-minute interval
                    'quantity' => 1,
                    ]],
                    'metadata' => [
                        'customer_name'   => $user->name,
                        'customer_email'  => $user->email,
                        'order_id'        => $order->id,
                    ],
                    'success_url' => $successUrl,
                ];
            }
            // ✅ Create Stripe Checkout Session with a 1-Minute Recurring Payment Plan
            // $checkoutData = [
            //     //'payment_method_types' => ['card', 'bancontact', 'ideal', 'p24', 'sofort', 'klarna', 'giropay', 'eps'],
            //     'payment_method_types' => ['card','bancontact', 'ideal', 'klarna'],
            //     'mode'                 => 'subscription',
            //     'customer'             => $customer->id,
            //     'billing_address_collection' => 'required',
            //     'line_items' => [[
            //         'price'    => $subscribe->price_id, // ✅ Use a test price with a 1-minute interval
            //         'quantity' => 1,
            //     ]],
            //     'metadata' => [
            //         'customer_name'   => $user->name,
            //         'customer_email'  => $user->email,
            //         'order_id'        => $order->id,
            //     ],
            //     'success_url' => $successUrl,
            // ];

            // Add cancel_url only if mobileHeader != 1
            if (session()->get('mobileHeader') != 1) {
                $checkoutData['cancel_url'] = $this->makeCallbackUrl('cancel');
            }
            // Create the checkout session
            $checkout = Session::create($checkoutData);

            // Store session in Laravel session
            session()->put($this->order_session_key, $order->id);

            if (session()->get('mobileHeader') == 1) {
                return apiResponse2(1, 'retrieved', 'Payment Url', [
                    'url'        => $checkout->url,
                    'session_id' => $checkout->id,
                    'order_id'   => $order->id
                ]);
            }
           
            $Html = '<script src="https://js.stripe.com/v3/"></script>';
            $Html .= '<script type="text/javascript">let stripe = Stripe("' . env('STRIPE_KEY') . '");';
            $Html .= 'stripe.redirectToCheckout({ sessionId: "' . $checkout->id . '" }); </script>';

            echo $Html;
        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Subscription creation failed!',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function makeCallbackUrl($status)
    {
        return url("/payments/verify/Stripe?status=$status&session_id={CHECKOUT_SESSION_ID}");
    }
    
    private function makeRecurringCallbackUrl($status)
    {
        return url("/payments/recurringVerify/Stripe?status=$status&session_id={CHECKOUT_SESSION_ID}");
    }

    public function verify(Request $request)
    {
        $data = $request->all();
        Log::info('verify request CHANNEL : ', $data);
        $status = $data['status'];
        $order_id = session()->get($this->order_session_key, null) ?? $data['order_id'];
        Log::info('order_id : ', [$order_id]);
        session()->forget($this->order_session_key);

        $user = auth()->user() ?? apiAuth();
        if(!$user){
            if (session()->has('device_id')) {
                $userid = session('device_id');
            }
            if ($request->has('device_id') && $request->device_id != '') {
                $userid = $request->device_id;
            } 
        }else{
            $userid = $user->id;
        }
        
        $order = Order::where('id', $order_id)
            ->where('user_id', $userid)
            ->first();
        
        if ($status == 'success' and !empty($request->session_id) and !empty($order)) {
            //Stripe::setApiKey($this->api_secret);
            Stripe::setApikey(env('STRIPE_SECRET'));
            $session = Session::retrieve($request->session_id);
            Log::info('session id : ', [$session]);
            if (!empty($session) and $session->payment_status == 'paid') {
                $order->update([
                    'status' => Order::$paying
                ]);

                return $order;
            }
        }

        // is fail

        if (!empty($order)) {
            $order->update(['status' => Order::$fail]);
        }

        return $order;
    }

    public function recurringVerify(Request $request)
    {
        $data = $request->all();
        Log::info('verify request CHANNEL : ', $data);
        $status = $data['status'];

        $order_id = session()->get($this->order_session_key, null) ?? $data['order_id'];
        Log::info('order_id : ', [$order_id]);
        session()->forget($this->order_session_key);

        $user = auth()->user() ?? apiAuth();
        if(!$user){
            $userid = $request->device_id;
        }else{
            $userid = $user->id;
        }
        $order = Order::where('id', $order_id)
            ->where('user_id', $userid)
            ->first();

        // echo "<pre>";print_r($order);
        // die();
        if ($status == 'success' and !empty($request->session_id) and !empty($order)) {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = Session::retrieve($request->session_id);

            if (!empty($session) and $session->payment_status == 'paid') {
                $order->update([
                    'status' => Order::$paying
                ]);

                return $order;
            }
        }

        // is fail

        if (!empty($order)) {
            $order->update(['status' => Order::$fail]);
        }

        return $order;
    }
}