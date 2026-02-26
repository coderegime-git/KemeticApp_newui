<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mixins\Cashback\CashbackAccounting;
use App\Models\Accounting;
use App\Models\BecomeInstructor;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ReserveMeeting;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Sale;
use App\Models\TicketUser;
use App\Models\Subscribe;
use App\Models\Country;
use App\Models\Region;
use Stripe\Stripe;
use App\User;
use App\Models\Book;
use App\Models\BookOrder;
use Stripe\Webhook;
use Aws\S3\S3Client;
use App\Mail\SubscriptionRenewal;
use Aws\S3\Exception\S3Exception;
use App\Services\PdfResizerService;
use App\PaymentChannels\ChannelManager;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class PaymentController extends Controller
{
    protected $laragonCertPath;
    protected $pdfResizer;

    public function __construct()
    {
        $pdfResizer = new PdfResizerService();
        $this->pdfResizer = $pdfResizer;
        // Laragon certificate path - adjust if different
        $this->laragonCertPath = "C:/laragon/etc/ssl/cert.pem";
        
        // Alternative paths to check
        $alternativePaths = [
            "C:/laragon/etc/ssl/cert.pem",
            "C:/laragon/etc/ssl/cacert.pem",
            "C:/laragon/etc/ssl/certs/ca-bundle.crt",
            base_path("cacert.pem"), // If you want to store in your project
            storage_path("app/certs/cacert.pem"), // Custom storage path
        ];
        
        // Find the first existing certificate file
        foreach ($alternativePaths as $path) {
            if (file_exists($path)) {
                $this->laragonCertPath = $path;
                break;
            }
        }
    }

    public function handleWebhook(Request $request)
    {
        try {
            // Set Stripe API key from .env file
            Stripe::setApiKey(env('STRIPE_KEY'));
    
            // Retrieve webhook payload
            $payload = $request->all();
            Log::info('Stripe Webhook Received', $payload);
    
            // Ensure the 'type' key exists
            if (!isset($payload['type'])) {
                return response()->json(['error' => 'Invalid webhook payload'], 400);
            }
    
            // Handle successful payment event
            if ($payload['type'] === 'invoice.payment_succeeded') {
                $invoice = $payload['data']['object'];
    
                // Ensure necessary keys exist in the payload
                if (!isset($invoice['customer'], $invoice['subscription'])) {
                    return response()->json(['error' => 'Missing customer or subscription ID'], 400);
                }
    
                $customerId = $invoice['customer'];
                $subscriptionId = $invoice['subscription'];
    
                // Find the user based on Stripe Customer ID
                $user = User::where('stripe_customer_id', $customerId)->first();
    
                if ($user) {
                    // Update subscription status in the database
                    $user->update([
                        'subscription_status' => 'active',
                        'subscription_id' => $subscriptionId,
                    ]);
    
                    // Find the latest subscription sale
                    $lastSubscribeSale = Sale::where('buyer_id', $user->id)
                        ->where('type', Sale::$subscribe)
                        ->whereNull('refund_at')
                        ->latest('created_at')
                        ->first();
    
                    if ($lastSubscribeSale) {
                        $newEndDate = $invoice['lines']['data'][0]['period']['end'];
                        $lastSubscribeSale->update(['created_at' => $newEndDate]);
    
                        Log::info("Subscription extended to: " . date('Y-m-d H:i:s', $newEndDate));
                    } else {
                        Log::warning("No previous subscription sale found for user: " . $user->id);
                    }
    
                    return response()->json(['status' => 'success', 'message' => 'Subscription updated'], 200);
                } else {
                    Log::warning("User not found for Stripe customer ID: " . $customerId);
                }
            }
    
            // Handle subscription cancellation
            if ($payload['type'] === 'customer.subscription.deleted') {
                $subscription = $payload['data']['object'];
                $customerId = $subscription['customer'];
    
                $user = User::where('stripe_customer_id', $customerId)->first();
                if (!$user) {
                    Log::warning("User not found for Stripe customer ID: " . $customerId);
                    return response()->json(['error' => 'User not found'], 404);
                }
    
                // Delete subscription record from the sales table
                Sale::where('buyer_id', $user->id)
                    ->where('type', Sale::$subscribe)
                    ->delete();
    
                // Update user subscription status
                $user->update([
                    'subscription_status' => 'canceled',
                    'subscription_id' => null,
                ]);
    
                Log::info("Subscription cancelled for user {$user->id}. Sales record deleted.");
                return response()->json(['status' => 'success', 'message' => 'Subscription cancelled and sales record deleted'], 200);
            }
    
            return response()->json([
                'status' => 'success',
                'received_payload' => $payload
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Webhook Handling Error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    //  public function handleWebhook(Request $request)
    // {
    //     try {
    //         // Set Stripe API key from .env file
    //         Stripe::setApiKey(env('STRIPE_KEY'));

    //         // Retrieve webhook payload
    //         $payload = $request->all();
    //         Log::info('Stripe Webhook Received', $payload);

    //         // Ensure the 'type' key exists
    //         if (!isset($payload['type'])) {
    //             return response()->json(['error' => 'Invalid webhook payload'], 400);
    //         }

    //         // Handle successful payment event
    //         if ($payload['type'] === 'invoice.payment_succeeded') {
    //             $invoice = $payload['data']['object'];

    //             // Ensure necessary keys exist in the payload
    //             if (!isset($invoice['customer'], $invoice['subscription'])) {
    //                 return response()->json(['error' => 'Missing customer or subscription ID'], 400);
    //             }

    //             $customerId = $invoice['customer'];
    //             $subscriptionId = $invoice['subscription'];

    //             // Find the user based on Stripe Customer ID
    //             $user = User::where('stripe_customer_id', $customerId)->first();

    //             if ($user) {
    //                 // Update subscription status in the database
    //                 $user->update([
    //                     'subscription_status' => 'active',
    //                     'subscription_id' => $subscriptionId,
    //                 ]);

    //                 // Find the latest subscription sale
    //                 $lastSubscribeSale = Sale::where('buyer_id', $user->id)
    //                     ->where('type', Sale::$subscribe)
    //                     ->whereNull('refund_at')
    //                     ->latest('created_at')
    //                     ->first();

    //                 if ($lastSubscribeSale) {
    //                     $subscribe = $lastSubscribeSale->subscribe;

    //                     // Update the subscription end date
    //                     // $newEndDate = now()->addDays($subscribe->days)->timestamp; // Assuming `duration` is in days
    //                     $newEndDate = $invoice['lines']['data'][0]['period']['end'];

    //                     $lastSubscribeSale->update(['created_at' => $newEndDate]);

    //                     Log::info("Subscription extended to: " . $newEndDate);
    //                 } else {
    //                     Log::warning("No previous subscription sale found for user: " . $user->id);
    //                 }
                    
    //                 // Handle subscription cancellation
    //                 if ($event->type === 'customer.subscription.deleted') {
    //                     $subscription = $event->data->object;
    //                     $customerId = $subscription->customer;
            
    //                     $user = User::where('stripe_customer_id', $customerId)->first();
    //                     if (!$user) {
    //                         Log::warning("User not found for Stripe customer ID: " . $customerId);
    //                         return response()->json(['error' => 'User not found'], 404);
    //                     }
            
    //                     // Delete subscription record from the sales table
    //                     Sale::where('buyer_id', $user->id)
    //                         ->where('type', Sale::$subscribe)
    //                         ->delete();
            
    //                     // Update user subscription status
    //                     $user->update([
    //                         'subscription_status' => 'canceled',
    //                         'subscription_id' => null,
    //                     ]);
            
    //                     Log::info("Subscription cancelled for user {$user->id}. Sales record deleted.");
    //                     return response()->json(['status' => 'success', 'message' => 'Subscription cancelled and sales record deleted'], 200);
    //                 }


    //                 return response()->json(['status' => 'success', 'message' => 'Subscription updated'], 200);
    //             } else {
    //                 Log::warning("User not found for Stripe customer ID: " . $customerId);
    //             }
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'received_payload' => $payload
    //         ], 200);
    //     } catch (\Throwable $e) {
    //         Log::error('Webhook Handling Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Internal server error'], 500);
    //     }
    // }
    
    // public function handleWebhook(Request $request)
    // {
    //     try {
    //         // Set Stripe API key
    //         Stripe::setApiKey(env('STRIPE_SECRET'));
    
    //         // Retrieve webhook payload
    //         $payload = $request->all();
    //         Log::info('Stripe Webhook Received', $payload);
    
    //         // Check event type
    //         if (!isset($payload['type']) || $payload['type'] !== 'invoice.payment_succeeded') {
    //             return response()->json(['error' => 'Invalid webhook payload'], 400);
    //         }
    
    //         // Extract invoice details
    //         $invoice = $payload['data']['object'] ?? null;
    //         if (!$invoice || !isset($invoice['customer'], $invoice['subscription'], $invoice['lines']['data'][0]['period']['end'])) {
    //             Log::error("Missing required fields in invoice");
    //             return response()->json(['error' => 'Missing required invoice fields'], 400);
    //         }
    
    //         $customerId = $invoice['customer'];
    //         $subscriptionId = $invoice['subscription'];
    //         $newEndDate = $invoice['lines']['data'][0]['period']['end']; // Correct way to get new expiry
    
    //         // Find user by Stripe customer ID
    //         $user = User::where('stripe_customer_id', $customerId)->first();
    //         if (!$user) {
    //             Log::warning("User not found for Stripe customer ID: " . $customerId);
    //             return response()->json(['error' => 'User not found'], 404);
    //         }
    
    //         // Update subscription status
    //         $user->update([
    //             'subscription_status' => 'active',
    //             'subscription_id' => $subscriptionId,
    //         ]);
    
    //         // Find last subscription sale
    //         $lastSubscribeSale = Sale::where('buyer_id', $user->id)
    //             ->where('type', Sale::$subscribe)
    //             ->whereNull('refund_at')
    //             ->latest('created_at')
    //             ->first();
    
    //         if ($lastSubscribeSale) {
    //             $lastSubscribeSale->update(['created_at' => date('Y-m-d H:i:s', $newEndDate)]);
    
    //             Log::info("Subscription extended to: " . date('Y-m-d H:i:s', $newEndDate));
    //         } else {
    //             Log::warning("No previous subscription sale found for user: " . $user->id);
    //         }
            
    //         Log::info("Subscription updated for user {$user->id}. New expiry: " . date('Y-m-d H:i:s', $newEndDate));
    //         return response()->json(['status' => 'success', 'message' => 'Subscription updated'], 200);
    //     } catch (\Throwable $e) {
    //         Log::error('Webhook Handling Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Internal server error'], 500);
    //     }
    // }
    
    // public function handleWebhook(Request $request)
    // {
    //     try {
    //         Stripe::setApiKey(env('STRIPE_SECRET'));
    
    //         $endpointSecret = env('STRIPE_WEBHOOK_SECRET'); // Add this to .env
    //         $sigHeader = $request->header('Stripe-Signature');
    //         $payload = $request->getContent();
    
    //         try {
    //             $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
    //         } catch (\UnexpectedValueException $e) {
    //             Log::error('Invalid payload: ' . $e->getMessage());
    //             return response()->json(['error' => 'Invalid payload'], 400);
    //         } catch (\Stripe\Exception\SignatureVerificationException $e) {
    //             Log::error('Invalid signature: ' . $e->getMessage());
    //             return response()->json(['error' => 'Invalid signature'], 400);
    //         }
    
    //         Log::info('Stripe Webhook Received', ['type' => $event->type]);
    
    //         if ($event->type !== 'invoice.payment_succeeded') {
    //             return response()->json(['error' => 'Unhandled event type'], 400);
    //         }
    
    //         $invoice = $event->data->object;
            
    //         if (!$invoice || !isset($invoice->customer, $invoice->subscription, $invoice->lines->data[0]->period->end)) {
    //             Log::error("Missing required fields in invoice");
    //             return response()->json(['error' => 'Missing required invoice fields'], 400);
    //         }
    
    //         $customerId = $invoice->customer;
    //         $subscriptionId = $invoice->subscription;
    //         $newEndDate = $invoice->lines->data[0]->period->end; // Correct way to get expiry date
    
    //         $user = User::where('stripe_customer_id', $customerId)->first();
    //         if (!$user) {
    //             Log::warning("User not found for Stripe customer ID: " . $customerId);
    //             return response()->json(['error' => 'User not found'], 404);
    //         }
    
    //         // Update user's subscription
    //         $user->update([
    //             'subscription_status' => 'active',
    //             'subscription_id' => $subscriptionId,
    //         ]);
    
    //         // Find last subscription sale
    //         $lastSubscribeSale = Sale::where('buyer_id', $user->id)
    //             ->where('type', Sale::$subscribe)
    //             ->whereNull('refund_at')
    //             ->latest('created_at')
    //             ->first();
    
    //         if ($lastSubscribeSale) {
    //             $lastSubscribeSale->update(['created_at' => date('Y-m-d H:i:s', $newEndDate)]);
    //             Log::info("Subscription extended to: " . date('Y-m-d H:i:s', $newEndDate));
    //         } else {
    //             Log::warning("No previous subscription sale found for user: " . $user->id);
    //         }
    
    //         Log::info("Subscription updated for user {$user->id}. New expiry: " . date('Y-m-d H:i:s', $newEndDate));
    //         return response()->json(['status' => 'success', 'message' => 'Subscription updated'], 200);
    
    //     } catch (\Throwable $e) {
    //         Log::error('Webhook Handling Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Internal server error'], 500);
    //     }
    // }
    
    public function recurringPaymentRequest(Request $request)
    {
    //   echo "<pre>";
    //     var_dump($request); die;
        $this->validate($request, [
            'gateway' => 'required'
        ]);

        //dd('check1');

        $user = auth()->user();

        $gateway = $request->input('gateway');
        $orderId = $request->input('order_id');

        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if ($order->type === Order::$meeting) {
            $orderItem = OrderItem::where('order_id', $order->id)->first();
            $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
            $reserveMeeting->update(['locked_at' => time()]);
        }

        

        if ($gateway === 'credit') {

            if ($user->getAccountingCharge() < $order->total_amount) {
                $order->update(['status' => Order::$fail]);

                session()->put($this->order_session_key, $order->id);

                return redirect('/payments/status');
            }

            $order->update([
                'payment_method' => Order::$credit
            ]);

            $this->setPaymentAccounting($order, 'credit');

            $order->update([
                'status' => Order::$paid
            ]);

            session()->put($this->order_session_key, $order->id);

            return redirect('/payments/status');
        }

        $paymentChannel = PaymentChannel::where('id', $gateway)
            ->where('status', 'active')
            ->first();

        if (!$paymentChannel) {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('public.channel_payment_disabled'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $order->payment_method = Order::$paymentChannel;
        $order->save();
        
        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            // echo "<pre>";print_r($channelManager);die;
            //dd('check2');
            $redirect_url = $channelManager->recurringPaymentRequest($order);
            //dd($redirect_url);
            // echo $redirect_url;die;
           
            if (in_array($paymentChannel->class_name, PaymentChannel::$gatewayIgnoreRedirect)) {
                return $redirect_url;
            }
            return Redirect::away($redirect_url);
        } catch (\Exception $exception) {

            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }
    }
    
    protected $order_session_key = 'payment.order_id';

    // public function paymentRequest(Request $request)
    // {
    //     //echo "<pre>"; print_r($request->all()); die;
    //     $this->validate($request, [
    //         'gateway' => 'required'
    //     ]);
        

    //     $user = auth()->user();
    //     $user_as_a_guest=false;
        
    //     if(!$user){
    //         if (session()->has('device_id')) {
    //            // dd('check');
    //             $guestuser = new \stdClass(); // Create an empty object for guest users
    //             $guestuser->id = session('device_id');
    //             $user_as_a_guest=true;
    //             $userid = $guestuser->id;
                
    //         }
    //         else{
    //             return redirect('/cart');
    //         }
    //     }
    //     else{
    //         $userid = $user->id;
    //     }
        
    //     $gateway = $request->input('gateway');
    //     $orderId = $request->input('order_id');
       
    //     $order = Order::where('id', $orderId)
    //         ->where('user_id', $userid)
    //         ->first();

    //     session()->put($this->order_session_key, $orderId);
        
    //     if ($order->type === Order::$meeting) {
    //         $orderItem = OrderItem::where('order_id', $order->id)->first();
    //         $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
    //         $reserveMeeting->update(['locked_at' => time()]);
    //     }
        
    //     if ($gateway === 'credit') {

    //         if ($user->getAccountingCharge() < $order->total_amount) {
    //             $order->update(['status' => Order::$fail]);

    //             session()->put($this->order_session_key, $order->id);

    //             return redirect('/payments/status');
    //         }

    //         $order->update([
    //             'payment_method' => Order::$credit
    //         ]);

    //         $this->setPaymentAccounting($order, 'credit');

    //         $order->update([
    //             'status' => Order::$paid
    //         ]);

    //         session()->put($this->order_session_key, $order->id);

    //         return redirect('/payments/status');
    //     }

    //     $paymentChannel = PaymentChannel::where('id', $gateway)
    //         ->where('status', 'active')
    //         ->first();

    //     if (!$paymentChannel) {
    //         $toastData = [
    //             'title' => trans('cart.fail_purchase'),
    //             'msg' => trans('public.channel_payment_disabled'),
    //             'status' => 'error'
    //         ];
    //         return back()->with(['toast' => $toastData]);
    //     }
       

    //     $order->payment_method = Order::$paymentChannel;
    //     $order->save();
    //     //print_r($order);
    //     try {
    //         $channelManager = ChannelManager::makeChannel($paymentChannel);
    //         //print_r($channelManager);
             
    //         $redirect_url = $channelManager->paymentRequest($order);

    //         if ($redirect_url instanceof \Illuminate\Http\Response) {
    //             return $redirect_url;
    //         }

    //         if (is_string($redirect_url)) {
    //             return response($redirect_url);
    //         }

    //           //print_r($redirect_url);
    //          // exit();
    //         if (in_array($paymentChannel->class_name, PaymentChannel::$gatewayIgnoreRedirect)) {
    //             return $redirect_url;
    //         }

    //         if ($paymentChannel->class_name === 'Stripe') {
    //             return response($redirect_url); // Return the HTML as response
    //         }

    //         return Redirect::away($redirect_url);
    //     } catch (\Exception $exception) {   
          
    //         $toastData = [
    //             'title' => trans('cart.fail_purchase'),
    //             'msg' => trans('cart.gateway_error'),
    //             'status' => 'error'
    //         ];
    //         return back()->with(['toast' => $toastData]);
    //     }
    // }

    public function paymentRequest(Request $request)
    {
        $this->validate($request, [
            'gateway' => 'required'
        ]);
        
        $user = auth()->user();
        $user_as_a_guest = false;
        
        if (!$user) {
            if (session()->has('device_id')) {
                $guestuser = new \stdClass();
                $guestuser->id = session('device_id');
                $user_as_a_guest = true;
                $userid = $guestuser->id;
            } else {
                return redirect('/cart');
            }
        } else {
            $userid = $user->id;
        }
        
        $gateway = $request->input('gateway');
        $orderId = $request->input('order_id');
    
        $order = Order::where('id', $orderId)
            ->where('user_id', $userid)
            ->first();

        if (!$order) {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.order_not_found'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        session()->put($this->order_session_key, $orderId);
        
        if ($order->type === Order::$meeting) {
            $orderItem = OrderItem::where('order_id', $order->id)->first();
            $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
            $reserveMeeting->update(['locked_at' => time()]);
        }
        
        if ($gateway === 'credit') {
            if ($user && $user->getAccountingCharge() < $order->total_amount) {
                $order->update(['status' => Order::$fail]);
                session()->put($this->order_session_key, $order->id);
                return redirect('/payments/status');
            }

            $order->update([
                'payment_method' => Order::$credit
            ]);

            $this->setPaymentAccounting($order, 'credit');
            $order->update([
                'status' => Order::$paid
            ]);

            session()->put($this->order_session_key, $order->id);
            return redirect('/payments/status');
        }

        $paymentChannel = PaymentChannel::where('id', $gateway)
            ->where('status', 'active')
            ->first();

        if (!$paymentChannel) {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('public.channel_payment_disabled'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }
    
        $order->payment_method = Order::$paymentChannel;
        $order->save();

        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            $redirect_url = $channelManager->paymentRequest($order);
            
            // Log the redirect URL for debugging
            \Log::info('Payment redirect URL', [
                'gateway' => $paymentChannel->class_name,
                'redirect_url' => $redirect_url,
                'order_id' => $order->id
            ]);
            
            if (in_array($paymentChannel->class_name, PaymentChannel::$gatewayIgnoreRedirect)) {
                return $redirect_url;
            }

            if ($paymentChannel->class_name === 'Stripe') {
                if (empty($redirect_url)) {
                    throw new \Exception('Stripe returned empty redirect URL');
                }
                
                // If it's a JSON response from mobile, return as is
                if (is_array($redirect_url) && isset($redirect_url['success'])) {
                    return response()->json($redirect_url);
                }
                
                // If it's a string URL, redirect to it
                if (is_string($redirect_url) && filter_var($redirect_url, FILTER_VALIDATE_URL)) {
                    return redirect()->away($redirect_url);
                }
                
                // If it's HTML content, return as response
                return response($redirect_url);
            }
            
            return Redirect::away($redirect_url);
            
        } catch (\Exception $exception) {   
            \Log::error('Payment request error: ' . $exception->getMessage(), [
                'exception' => $exception,
                'order_id' => $order->id,
                'gateway' => $gateway,
                'trace' => $exception->getTraceAsString()
            ]);
            
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error') . ': ' . $exception->getMessage(),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }
    }

    public function paymentVerify(Request $request, $gateway)
    {
        //echo 1;die;
        Log::info('paymentVerify CONTROLLER : ', $request->all());
        Log::info('gateway NAME : ', [$gateway]);
        $paymentChannel = PaymentChannel::where('class_name', $gateway)
            ->where('status', 'active')
            ->first();

        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            // echo "<pre>"; print_r($channelManager); 
            // die('ghjgh');
            $order = $channelManager->verify($request);
            //echo "<pre>"; print_r($order); die;
            return $this->paymentOrderAfterVerify($order);

        } catch (\Exception $exception) {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];
            return redirect('cart')->with(['toast' => $toastData]);
        }
    }
    
    public function recurringPaymentVerify(Request $request, $gateway)
    {
        // echo 1;die;
        Log::info('paymentVerify CONTROLLER : ', $request->all());
        Log::info('gateway NAME : ', [$gateway]);
        $paymentChannel = PaymentChannel::where('class_name', $gateway)
            ->where('status', 'active')
            ->first();

        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            // die('ghjgh');
            $order = $channelManager->recurringVerify($request);
            //  print_r($order);die;
            return $this->paymentOrderAfterVerify($order);

        } catch (\Exception $exception) {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];
            // echo $exception->getMessage();die;
            return redirect('cart')->with(['toast' => $toastData]);
        }
    }

    /*
     * | this methode only run for payku.result
     * */
    public function paykuPaymentVerify(Request $request, $id)
    {
        $paymentChannel = PaymentChannel::where('class_name', PaymentChannel::$payku)
            ->where('status', 'active')
            ->first();

        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);

            $request->request->add(['transaction_id' => $id]);

            $order = $channelManager->verify($request);

            return $this->paymentOrderAfterVerify($order);

        } catch (\Exception $exception) {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];
            return redirect('cart')->with(['toast' => $toastData]);
        }
    }

    private function paymentOrderAfterVerify($order)
    {
        //echo "<pre>"; print_r($order); die;
        if (!empty($order)) {

            if ($order->status == Order::$paying) {
                $this->setPaymentAccounting($order);

                $order->update(['status' => Order::$paid]);

                if ($order->status == Order::$paid) {
                    $this->handleLuluPrintJobAfterPayment($order);
                }
            } else {
                if ($order->type === Order::$meeting) {
                    $orderItem = OrderItem::where('order_id', $order->id)->first();

                    if ($orderItem && $orderItem->reserve_meeting_id) {
                        $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();

                        if ($reserveMeeting) {
                            $reserveMeeting->update(['locked_at' => null]);
                        }
                    }
                }
            }

            session()->put($this->order_session_key, $order->id);

            return redirect('/payments/status');
        } else {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];

            return redirect('cart')->with($toastData);
        }
    }

    public function setPaymentAccounting($order, $type = null)
    {
        
        $cashbackAccounting = new CashbackAccounting();

        if ($order->is_charge_account) {
            Accounting::charge($order);

            $cashbackAccounting->rechargeWallet($order);
        } else {
            //echo "<pre>"; print_r($order->orderItems); die;
            foreach ($order->orderItems as $orderItem) {
                $sale = Sale::createSales($orderItem, $order->payment_method);

                if (!empty($orderItem->reserve_meeting_id)) {
                    $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
                    $reserveMeeting->update([
                        'sale_id' => $sale->id,
                        'reserved_at' => time()
                    ]);

                    $reserver = $reserveMeeting->user;

                    if ($reserver) {
                        $this->handleMeetingReserveReward($reserver);
                    }
                }

                if (!empty($orderItem->gift_id)) {
                    $gift = $orderItem->gift;

                    $gift->update([
                        'status' => 'active'
                    ]);

                    $gift->sendNotificationsWhenActivated($orderItem->total_amount);
                }

                if (!empty($orderItem->subscribe_id)) {
                    Accounting::createAccountingForSubscribe($orderItem, $type);
                } elseif (!empty($orderItem->promotion_id)) {
                    Accounting::createAccountingForPromotion($orderItem, $type);
                } elseif (!empty($orderItem->registration_package_id)) {
                    Accounting::createAccountingForRegistrationPackage($orderItem, $type);

                    if (!empty($orderItem->become_instructor_id)) {
                        BecomeInstructor::where('id', $orderItem->become_instructor_id)
                            ->update([
                                'package_id' => $orderItem->registration_package_id
                            ]);
                    }
                } elseif (!empty($orderItem->installment_payment_id)) {
                    Accounting::createAccountingForInstallmentPayment($orderItem, $type);

                    $this->updateInstallmentOrder($orderItem, $sale);
                } else {
                    // webinar and meeting and product and bundle

                    Accounting::createAccounting($orderItem, $type);
                    TicketUser::useTicket($orderItem);

                    if (!empty($orderItem->product_id)) {
                        $this->updateProductOrder($sale, $orderItem);
                    }

                    if(!empty($orderItem->book_id))
                    {
                        $this->updateBookOrder($sale, $orderItem);
                    }
                }
            }

            // Set Cashback Accounting For All Order Items
            $cashbackAccounting->setAccountingForOrderItems($order->orderItems);
        }

        Cart::emptyCart($order->user_id);
    }

    private function handleLuluPrintJobAfterPayment($order)
    {
        try {
            // Check if order contains book products
            foreach ($order->orderItems as $orderItem) {
                if (!empty($orderItem->book_id)) {
                    // dd('before getLuluPriceUsingCurl');
                    $this->getLuluPriceUsingCurl($orderItem->book_id, $orderItem->user_id);
                    // dd('after getLuluPriceUsingCurl');
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to initiate Lulu print job: ' . $e->getMessage());
            // Don't throw exception to avoid disrupting payment flow
        }
    }

    private function getLuluPriceUsingCurl($bookid, $userid, $token = null)
    {
        // dd('getLuluPriceUsingCurl', $bookid, $userid, $token);
        if (!$token) {
            // dd('toekn');
            $token = $this->getLuluAccessTokenUsingCurl();
        }
        // dd($token);
        // dd($token);
        
        // $url = env('LULU_BASE_URL', 'https://api.sandbox.lulu.com') . $endpoint;
        // $url = "https://api.sandbox.lulu.com/print-job-cost-calculations/";

        // try {    // Use NEW credentials after revoking the compromised ones!

        //     $pdfContent = file_get_contents($sourcePdfUrl);
            
        //     if ($pdfContent === false) {
        //         throw new Exception("Failed to download PDF from URL: $sourcePdfUrl");
        //     }
            
        //     $s3Client = new \Aws\S3\S3Client([
        //         'version' => 'latest',
        //         'region'  => env('AWS_DEFAULT_REGION', 'us-east-1'),
        //         'credentials' => [
        //             'key'    => env('AWS_ACCESS_KEY_ID', 'YOUR_NEW_KEY_HERE'),
        //             'secret' => env('AWS_SECRET_ACCESS_KEY', 'YOUR_NEW_SECRET_HERE'),
        //         ]
        //     ]);

        //     $bucket = env('AWS_BUCKET', 'lulu-pdfs-01');
        //     $fileName = 'lulu-uploads/' . uniqid() . '_' . time() . '.pdf';
            
        //     // Upload to S3 with public read access
        //     $result = $s3Client->putObject([
        //         'Bucket' => $bucket,
        //         'Key'    => $fileName,
        //         'Body' => $pdfContent,
        //         'ContentType' => 'application/pdf',
        //         'ACL'    => 'public-read', // This makes it publicly accessible
        //         'Metadata' => [
        //             'Uploaded-By' => 'Lulu-API',
        //             'Expires' => gmdate('D, d M Y H:i:s T', time() + 3600) // 1 hour expiry
        //         ]
        //     ]);

        //     $publicUrl = $result['ObjectURL'];
        //     Log::info("PDF uploaded to S3. Public URL: " . $publicUrl);

        // } catch (\Exception $e) {
        //     Log::error("S3 Upload failed: " . $e->getMessage());
        // } 

        

        // $pdfurl = "https://studiocaribbean.com/400page.pdf";
        // $pdfpathurl = "https://kemetic.app/store/1/Where-the-Crawdads-Sing.pdf";
        // // $pdfpathurl = "https://kemetic.app/store/1/pdf/traffic_pub_gen19.pdf";

        // // dd('hi');
        // $result = $this->pdfResizer->resizeForLulu($pdfpathurl, false);

        // $cover    = $this->pdfResizer->generateCoverFromPdf($pdfpathurl, $result['page_count']);
        // $coverurl = $cover['local_path'];
        // dd($coverurl);

        // Simulate your API call structure
        // dd($pdfpathurl);
        // $pdfurl = $result['lulu_pdf_url'];

        // $sourcePdfUrl = "https://kemetic.app/store/1/pdf/400page.pdf";
        // $title = "Test Print Job via Curl";
        // $pdfurl = "https://kemetic.app/store/lulu/interior/interior_1768311771.pdf";
        // $coverurl = "https://kemetic.app/store/lulu/cover/cover_1768311014.pdf";
        // dd($bookid);
        $book = Book::where('id', $bookid)->where('type', 'Print')->first();

       
        if (!$book) {
            throw new \Exception("Book not found with ID: $bookid");
        }
        // dd($book);
        
        // 2. FETCH USER/BUYER DATA
        $user = User::select('id', 'full_name', 'email', 'mobile', 'country_id', 'province_name', 
                            'city_name', 'address', 'zip_code')
                    ->find($userid);
        // dd($user);
        if (!$user) {
            throw new \Exception("User not found with ID: $userid");
        }

        if ($user->country_id) {
            $country = Region::select('title')
                            ->where('id', $user->country_id)
                            ->where('type', Region::$country)
                            ->first();
            
            if ($country) {
                $countryName = $country->title;
            }
        }

        $countrycode = Country::where('country_name', $countryName)->value('country_code');
        // 4. FORMAT PHONE
        $phone = $user->mobile ?: '+1 206 555 0100';
        if (!str_starts_with($phone, '+')) {
            $phone = '+1' . preg_replace('/[^0-9]/', '', $phone);
        }

        $printurl = 'https://api.lulu.com/print-jobs/';
        
        $quantity = 1;

        $address = $user->address;

        // If address is longer than 30 chars, split intelligently
        if (strlen($address) > 30) {
            // Find the last space before position 30
            $splitPos = strrpos(substr($address, 0, 31), ' ');
            
            // If no space found, force split at 30
            $splitPos = $splitPos ?: 30;
            
            $street1 = substr($address, 0, $splitPos);
            $street2 = trim(substr($address, $splitPos));
        } else {
            $street1 = $address;
            $street2 = "";
        }


        $data = [
            "contact_email" => $user->email ?: "info@kemetic.app",
            "external_id" => "Kemetic APP",
            "line_items" => [
                [
                    "external_id" => "item-reference-1",
                    "printable_normalization" =>[
                        "cover" => [
                            "source_url" => url($book->cover_pdf),
                        ],
                        "interior" => [
                            "source_url" => url($book->url),
                            "page_count" => $book->page_count // You need to add the correct page count
                        ],
                        "pod_package_id" => "0600X0900BWSTDPB060UW444MXX"
                    ],
                    "title" => $book->title,
                    "quantity" => 1, 
                ]
            ],
            "production_delay" => 120,
            "shipping_address" => [
                "city" =>  $user->city_name,
                "country_code" => $countrycode,
                "name" => $user->full_name,
                "phone_number" => $phone,
                "state_code" => $user->province_name,
                "postcode" => $user->zip_code,
                "street1" => $street1,
                "street2" => $street2

                // "city" => "L\u00fcbeck",
                // "country_code" => "GB",
                // "name" => "Kemetic User",
                // "phone_number" => "844-212-0689",
                // "state_code" => "PO1 3AX",
                // "postcode" => "",
                // "street1" => "Holstenstr. 48"
            ],
            "shipping_level" => "MAIL"
        ];

        // dd($data);
        
        $printcurl = curl_init();
        curl_setopt_array($printcurl, [
            CURLOPT_URL => $printurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Cache-Control: no-cache',
                'Content-Type: application/json'
            ],
        ]);

        // Certificate verification handling
        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            $options[CURLOPT_CAINFO] = $this->laragonCertPath;
            $options[CURLOPT_CAPATH] = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }

        if (!empty($data)) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($printcurl, $options);

        $response = curl_exec($printcurl);
        $httpCode = curl_getinfo($printcurl, CURLINFO_HTTP_CODE);
        $error = curl_error($printcurl);
        if ($error) {
            $errorNo = curl_errno($printcurl);
            \Log::error("cURL Error #{$errorNo}: {$error}");
            \Log::error("Certificate path used: " . ($this->laragonCertPath ?? 'none'));
            \Log::error("File exists: " . (file_exists($this->laragonCertPath) ? 'Yes' : 'No'));
        }

        // dd($data);
        // dd($response);

        $responseData = json_decode($response, true);
       
        return $responseData;
    }

    private function getLuluAccessTokenUsingCurl()
    {
        // dd('getLuluAccessTokenUsingCurl');
        $url = "https://api.lulu.com/auth/realms/glasstree/protocol/openid-connect/token";
        //$url = "https://api.sandbox.lulu.com/auth/realms/glasstree/protocol/openid-connect/token";
        $authorization = "OWY2MDViMTUtNmMzYy00OWU1LTkxOWItODRmNzM0MWEyMjgzOk50cVpOa2N2aE1nNlJpb25FaEVSbWpyZW5EQTJYU3dW";
        // $authorization = "9f605b15-6c3c-49e5-919b-84f7341a2283:NtqZNkcvhMg6RionEhERmjrenDA2XSwV"; // Basic xxxx

        $laragonCertPath = "C:/laragon/etc/ssl/cert.pem";
        $verifyOption = file_exists($laragonCertPath) ? $laragonCertPath : false;

        
        $curl = curl_init();
         $authorization = "OWY2MDViMTUtNmMzYy00OWU1LTkxOWItODRmNzM0MWEyMjgzOk50cVpOa2N2aE1nNlJpb25FaEVSbWpyZW5EQTJYU3dW";
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.lulu.com/auth/realms/glasstree/protocol/openid-connect/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $authorization
            ],
        ]);
        
        
        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            // Use Laragon certificate
            $options[CURLOPT_CAINFO] = $this->laragonCertPath;
            $options[CURLOPT_CAPATH] = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            // Disable SSL verification if no certificate found (NOT RECOMMENDED for production)
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            
            \Log::warning('SSL certificate verification disabled. Certificate file not found.');
        }
        
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
          
        if ($error) {
            $errorNo = curl_errno($curl);
            \Log::error("cURL Error #{$errorNo}: {$error}");
            \Log::error("Certificate path used: " . ($this->laragonCertPath ?? 'none'));
            \Log::error("File exists: " . (file_exists($this->laragonCertPath) ? 'Yes' : 'No'));
        }
        
        curl_close($curl);

        $data = json_decode($response, true);

        // dd($data);
      
        // if ($httpCode !== 200) {
        //     throw new \Exception("Failed to get access token: " . ($data['error_description'] ?? 'Unknown error'));
        // }

        // dd($authorization, $response, $httpCode, $error, $data, curl_getinfo($curl), $curl);

        return $data['access_token'] ?? null;
    }

    public function payStatus(Request $request)
    {
        $orderId = $request->get('order_id', null);
        // die('jyghkjh');
        if (!empty(session()->get($this->order_session_key, null))) {
            $orderId = session()->get($this->order_session_key, null);
            session()->forget($this->order_session_key);
        }

        $user = auth()->user();
        
        if(!$user){
            if (session()->has('device_id')) {
                $guestuser = new \stdClass(); // Create an empty object for guest users
                $guestuser->id = session('device_id');
                $userId = $guestuser->id;
            }
            else{
                return redirect('/cart');
            }
        }
        else{
            $userId = $user->id;
        }

        

         $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->with(['orderItems' => function($query) {
                $query->with(['subscribe', 'webinar', 'bundle', 'product']);
            }])
            ->first();

        if (!$order) {
            return redirect('/cart')->with('error', 'Order not found');
        }

        // Check if this is a subscription order
        $isSubscription = $order->orderItems->contains('subscribe_id', '!=', null);
        
        if ($isSubscription) {
            return $this->handleSubscriptionConfirmation($order, $userId);
        }

        // Handle regular order confirmation
        return $this->handleRegularOrderConfirmation($order);
        
        
        // $order = Order::where('id', $orderId)
        //     ->where('user_id', auth()->id())
        //     ->first();
        
        // $order = Order::where('id', $orderId)
        //      ->where('user_id', $userid)
        //      ->first();
        
        // if (!empty($order)) {
        //     $data = [
        //         'pageTitle' => trans('public.cart_page_title'),
        //         'order' => $order,
        //     ];

        //     return view('web.default.cart.status_pay', $data);
        // }

        // return redirect('/panel');
    }

    private function handleSubscriptionConfirmation($order, $userId)
    {
        $subscriptionItem = $order->orderItems->where('subscribe_id', '!=', null)->first();
        
        if (!$subscriptionItem || !$subscriptionItem->subscribe) {
            return redirect('/cart')->with('error', 'Subscription not found');
        }

        $subscribe = $subscriptionItem->subscribe;
        
        // Get active subscription for user
        $activeSubscribe = Subscribe::getActiveSubscribe($userId);
        $isActive = $activeSubscribe && $activeSubscribe->id == $subscribe->id;

        $data = [
            'pageTitle' => 'Subscription Confirmation',
            'order' => $order,
            'subscribe' => $subscribe,
            'subscriptionItem' => $subscriptionItem,
            'isActive' => $isActive,
            'activeSubscribe' => $activeSubscribe,
        ];

        return view('web.default.pages.membership_confirm', $data);
    }

    private function handleRegularOrderConfirmation($order)
    {
        $data = [
            'pageTitle' => 'Order Confirmation',
            'order' => $order,
        ];
        
        return view('web.default.cart.status_pay', $data);
        //return view('web.default.cart.order_confirmation', $data);
    }

    public function getSubscriptionUsage($userId, $subscribeId)
    {
        $usage = [
            'used_count' => 0,
            'remaining' => 0,
            'days_used' => 0,
            'days_remaining' => 0
        ];

        $activeSubscribe = Subscribe::getActiveSubscribe($userId);
        
        if ($activeSubscribe && $activeSubscribe->id == $subscribeId) {
            $usage['used_count'] = $activeSubscribe->used_count ?? 0;
            $usage['remaining'] = $activeSubscribe->infinite_use ? 'Unlimited' : 
                                max(0, ($activeSubscribe->usable_count - $usage['used_count']));
            $usage['days_used'] = Subscribe::getDayOfUse($userId);
            $usage['days_remaining'] = max(0, ($activeSubscribe->days - $usage['days_used']));
        }

        return $usage;
    }

    private function handleMeetingReserveReward($user)
    {
        if ($user->isUser()) {
            $type = Reward::STUDENT_MEETING_RESERVE;
        } else {
            $type = Reward::INSTRUCTOR_MEETING_RESERVE;
        }

        $meetingReserveReward = RewardAccounting::calculateScore($type);

        RewardAccounting::makeRewardAccounting($user->id, $meetingReserveReward, $type);
    }

    private function updateBookOrder($sale, $orderItem)
    {
        $book = $orderItem->book;

        $status = BookOrder::$waitingDelivery;

        if ($book and ($book->type == 'E-book' or $book->type == 'Audio Book')) {
            $status = BookOrder::$success;
        }

        BookOrder::where('book_id', $orderItem->book_id)
            ->where(function ($query) use ($orderItem) {
                $query->where(function ($query) use ($orderItem) {
                    $query->whereNotNull('buyer_id');
                    $query->where('buyer_id', $orderItem->user_id);
                });

                if ($orderItem->gift_id) {
                    $query->orWhere(function ($query) use ($orderItem) {
                        $query->whereNotNull('gift_id');
                        $query->where('gift_id', $orderItem->gift_id);
                    });
                }
            })
        ->update([
            'sale_id' => $sale->id,
            'status' => $status,
        ]);
    }

    private function updateProductOrder($sale, $orderItem)
    {
        $product = $orderItem->product;

        $status = ProductOrder::$waitingDelivery;

        if ($product and $product->isVirtual()) {
            $status = ProductOrder::$success;
        }

        ProductOrder::where('product_id', $orderItem->product_id)
            ->where(function ($query) use ($orderItem) {
                $query->where(function ($query) use ($orderItem) {
                    $query->whereNotNull('buyer_id');
                    $query->where('buyer_id', $orderItem->user_id);
                });

                $query->orWhere(function ($query) use ($orderItem) {
                    $query->whereNotNull('gift_id');
                    $query->where('gift_id', $orderItem->gift_id);
                });
            })
            ->update([
                'sale_id' => $sale->id,
                'status' => $status,
            ]);

        if ($product and $product->getAvailability() < 1) {
            $notifyOptions = [
                '[p.title]' => $product->title,
            ];
            sendNotification('product_out_of_stock', $notifyOptions, $product->creator_id);
        }
    }

    private function updateInstallmentOrder($orderItem, $sale)
    {
        $installmentPayment = $orderItem->installmentPayment;

        if (!empty($installmentPayment)) {
            $installmentOrder = $installmentPayment->installmentOrder;

            $installmentPayment->update([
                'sale_id' => $sale->id,
                'status' => 'paid',
            ]);

            /* Notification Options */
            $notifyOptions = [
                '[u.name]' => $installmentOrder->user->full_name,
                '[installment_title]' => $installmentOrder->installment->main_title,
                '[time.date]' => dateTimeFormat(time(), 'j M Y - H:i'),
                '[amount]' => handlePrice($installmentPayment->amount),
            ];

            if ($installmentOrder and $installmentOrder->status == 'paying' and $installmentPayment->type == 'upfront') {
                $installment = $installmentOrder->installment;

                if ($installment) {
                    if ($installment->needToVerify()) {
                        $status = 'pending_verification';

                        sendNotification("installment_verification_request_sent", $notifyOptions, $installmentOrder->user_id);
                        sendNotification("admin_installment_verification_request_sent", $notifyOptions, 1); // Admin
                    } else {
                        $status = 'open';

                        sendNotification("paid_installment_upfront", $notifyOptions, $installmentOrder->user_id);
                    }

                    $installmentOrder->update([
                        'status' => $status
                    ]);

                    if ($status == 'open' and !empty($installmentOrder->product_id) and !empty($installmentOrder->product_order_id)) {
                        $productOrder = ProductOrder::query()->where('installment_order_id', $installmentOrder->id)
                            ->where('id', $installmentOrder->product_order_id)
                            ->first();

                        $product = Product::query()->where('id', $installmentOrder->product_id)->first();

                        if (!empty($product) and !empty($productOrder)) {
                            $productOrderStatus = ProductOrder::$waitingDelivery;

                            if ($product->isVirtual()) {
                                $productOrderStatus = ProductOrder::$success;
                            }

                            $productOrder->update([
                                'status' => $productOrderStatus
                            ]);
                        }
                    }
                }
            }


            if ($installmentPayment->type == 'step') {
                sendNotification("paid_installment_step", $notifyOptions, $installmentOrder->user_id);
                sendNotification("paid_installment_step_for_admin", $notifyOptions, 1); // For Admin
            }

        }
    }

}
