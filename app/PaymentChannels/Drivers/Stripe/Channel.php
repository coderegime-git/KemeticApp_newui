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
        $reqCurrency = request()->input('currency');
        $reqAmount   = request()->input('amount');

        // Determine target currency
        if (!empty($reqCurrency)) {
            $currency = $reqCurrency;
        } else {
            $currency = currency();
            $currency = $currency == 'USD' ? 'EUR' : $currency;
        }

        // If the frontend already passed the live-rate total, use it directly.
        // Otherwise fetch the live rate from the same exchangerate-api.com the JS uses.
        if (!empty($reqAmount) && (float)$reqAmount > 0) {
            $priceFloat = (float) $reqAmount;
            Log::info('Stripe paymentRequest - using frontend-supplied amount', ['amount' => $priceFloat, 'currency' => $currency]);
        } else {
            // Fetch live rate from exchangerate-api.com (EUR base)
            $liveRate = null;
            $apiKey   = '571fa201a47780cdeaa90825';
            try {
                $apiUrl  = "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/EUR";
                $context = stream_context_create(['http' => ['timeout' => 5]]);
                $raw     = @file_get_contents($apiUrl, false, $context);
                if ($raw) {
                    $data = json_decode($raw, true);
                    if (!empty($data['result']) && $data['result'] === 'success' && !empty($data['conversion_rates'][$currency])) {
                        $liveRate = (float) $data['conversion_rates'][$currency];
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Stripe paymentRequest - live rate API failed: ' . $e->getMessage());
            }

            if ($liveRate !== null) {
                // order->total_amount is stored in EUR base
                $priceFloat = round($order->total_amount * $liveRate, 2);
                Log::info('Stripe paymentRequest - using live API rate', ['rate' => $liveRate, 'amount' => $priceFloat, 'currency' => $currency]);
            } else {
                // Fallback to DB rate
                $priceFloat = round($this->makeAmountByCurrency($order->total_amount, $this->currency), 2);
                Log::info('Stripe paymentRequest - using DB rate fallback', ['amount' => $priceFloat, 'currency' => $currency]);
            }
        }

        $priceCents = (int) round($priceFloat * 100);

        $generalSettings = getGeneralSettings();

        Log::info('Stripe paymentRequest - currency: ' . $currency . ', amount_cents: ' . $priceCents . ', order_id: ' . $order->id);

        Stripe::setApikey(env('STRIPE_SECRET'));
        $successUrl = (session()->get('mobileHeader') == 1)
            ? 'https://kemetic.app/paymentSuccess'
            : $this->makeCallbackUrl('success');
        
        // EUR-specific payment methods (iDEAL, bancontact etc. only work with EUR)
        if (strtolower($currency) === 'eur') {
            $paymentMethods = ['ideal', 'card', 'p24', 'klarna', 'giropay', 'eps', 'bancontact'];
        } elseif (strtolower($currency) === 'gbp') {
            $paymentMethods = ['card', 'klarna', 'bacs_debit'];
        } else {
            // CAD, USD, INR and others — only card is universally safe
            $paymentMethods = ['card'];
        }

        $checkoutData = [
            'payment_method_types' => $paymentMethods,
            'mode' => 'payment',
            'billing_address_collection' => 'required',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => strtolower($currency),
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
                'currency_requested' => $currency,
            ],
            'success_url' => $successUrl,
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
            
            // dd($user->stripe_customer_id);
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

            // dd($customer);

            if($subscribe->id == '4'){

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
                        'address' => [
                            'line1'       => $user->address ?? '6th Floor, Indore',
                            'line2'       => $user->address ?? '1st Floor, Indore',
                            'city'        => $city->title ?? 'Indore',
                            'state'       => $state->title ?? 'Madhya Pradesh',
                            'postal_code' => $user->zip_code ?? '452001',
                            'country'     => 'IN',
                        ],
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

            // dd($checkoutData);
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
        // dd($data);
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
            // dd(env('STRIPE_SECRET'));
            $session = Session::retrieve($request->session_id);
            
            Log::info('session id : ', [$session]);
            if (!empty($session) and $session->payment_status == 'paid') {

                $order->update([
                    'status' => Order::$paying
                ]);
                // dd($order);
                return $order;
            }
        }
        // dd('payment failed');
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