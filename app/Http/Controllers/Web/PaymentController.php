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
use App\PaymentChannels\ChannelManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionRenewal;
use Stripe\Stripe;
use App\User;
use Stripe\Webhook;

class PaymentController extends Controller
{
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
                }
            }

            // Set Cashback Accounting For All Order Items
            $cashbackAccounting->setAccountingForOrderItems($order->orderItems);
        }

        Cart::emptyCart($order->user_id);
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
