<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\TicketUser;
use App\PaymentChannels\ChannelManager;
use App\User;
use App\Models\Country;
use App\Models\Book;
use App\Models\BookOrder;
use App\Models\ProductOrder;
use App\Mixins\Cashback\CashbackAccounting;
use App\Models\BecomeInstructor;
use App\Models\Region;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use App\Services\PdfResizerService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;


class PaymentsController extends Controller
{
    protected $order_session_key;
    protected $laragonCertPath;
    protected $pdfResizer;

    public function __construct()
    {
        $this->order_session_key = 'payment.order_id';

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

    public function paymentByCredit(Request $request)
    {
        validateParam($request->all(), [
            'order_id' => ['required',
                Rule::exists('orders', 'id')->where('status', Order::$pending),

            ],
        ]);

        $user = apiAuth();
        $orderId = $request->input('order_id');

        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();


        if ($order->type === Order::$meeting) {
            $orderItem = OrderItem::where('order_id', $order->id)->first();
            $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
            $reserveMeeting->update(['locked_at' => time()]);
        }

        if ($user->getAccountingCharge() < $order->amount) {
            $order->update(['status' => Order::$fail]);

            return apiResponse2(0, 'not_enough_credit', trans('api.payment.not_enough_credit'));


        }

        $order->update([
            'payment_method' => Order::$credit
        ]);

        $this->setPaymentAccounting($order, 'credit');

        $order->update([
            'status' => Order::$paid
        ]);

        return apiResponse2(1, 'paid', trans('api.payment.paid'));

    }


    public function paymentRequest(Request $request)
    {
        $user = apiAuth();
        $user_as_a_guest=false;
        if(!$user){
            $guestuser = new \stdClass(); // Create an empty object for guest users
            $guestuser->id = $request->input('device_id') ?? null;
            $user_as_a_guest=true;
            if (!$guestuser->id) {
                return apiResponse2(0, 'invalid_device_id', 'Device ID is required for guest users.');
            }
            $userid = $guestuser->id;
        }
        else{
            $userid = $user->id;
        }
        validateParam($request->all(), [
            'gateway_id' => ['required',
                Rule::exists('payment_channels', 'id')
            ],
            'order_id' => ['required',
                Rule::exists('orders', 'id')->where('status', Order::$pending)
                    ->where('user_id', $userid),

            ],
        ]);


        $gateway = $request->input('gateway_id');
        $orderId = $request->input('order_id');

        $order = Order::where('id', $orderId)
            ->where('user_id', $userid)
            ->first();

        session()->put($this->order_session_key, $orderId);
        
        if ($order->type === Order::$meeting) {
            $orderItem = OrderItem::where('order_id', $order->id)->first();
            $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
            $reserveMeeting->update(['locked_at' => time()]);
        }


        $paymentChannel = PaymentChannel::where('id', $gateway)
            ->where('status', 'active')
            ->first();

        if (!$paymentChannel) {
            return apiResponse2(0, 'disabled_gateway', trans('api.payment.disabled_gateway'));
        }

        $order->payment_method = Order::$paymentChannel;
        $order->save();
        $mobileHeader = $request->header('mobileHeader');
        session()->put('mobileHeader', $mobileHeader);
        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            $redirect_url = $channelManager->paymentRequest($order);


            if (in_array($paymentChannel->class_name, ['Paytm', 'Payu', 'Zarinpal', 'Stripe', 'Paysera', 'Cashu', 'Iyzipay', 'MercadoPago'])) {

                return $redirect_url;
            }

            return $redirect_url;
            //      dd($redirect_url) ;
            return Redirect::away($redirect_url);

        } catch (\Exception $exception) {

            if (!$paymentChannel) {
                return apiResponse2(0, 'gateway_error', trans('api.payment.gateway_error'));
            }

        }
    }

    public function paymentsubscribeRequest(Request $request)
    {
        $user = apiAuth();
        $user_as_a_guest=false;
        if(!$user){
            $guestuser = new \stdClass(); // Create an empty object for guest users
            $guestuser->id = $request->input('device_id') ?? null;
            $user_as_a_guest=true;
            if (!$guestuser->id) {
                return apiResponse2(0, 'invalid_device_id', 'Device ID is required for guest users.');
            }
            $userid = $guestuser->id;
        }
        else{
            $userid = $user->id;
        }
        validateParam($request->all(), [
            'gateway_id' => ['required',
                Rule::exists('payment_channels', 'id')
            ],
            'order_id' => ['required',
                Rule::exists('orders', 'id')->where('status', Order::$pending)
                    ->where('user_id', $userid),

            ],
        ]);


        $gateway = $request->input('gateway_id');
        $orderId = $request->input('order_id');

        $order = Order::where('id', $orderId)
            ->where('user_id', $userid)
            ->first();

        session()->put($this->order_session_key, $orderId);
        
        if ($order->type === Order::$meeting) {
            $orderItem = OrderItem::where('order_id', $order->id)->first();
            $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
            $reserveMeeting->update(['locked_at' => time()]);
        }


        $paymentChannel = PaymentChannel::where('id', $gateway)
            ->where('status', 'active')
            ->first();

        if (!$paymentChannel) {
            return apiResponse2(0, 'disabled_gateway', trans('api.payment.disabled_gateway'));
        }

        $order->payment_method = Order::$paymentChannel;
        $order->save();
        $mobileHeader = $request->header('mobileHeader');
        session()->put('mobileHeader', $mobileHeader);
        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            $redirect_url = $channelManager->recurringPaymentRequest($order);

            if (in_array($paymentChannel->class_name, ['Paytm', 'Payu', 'Zarinpal', 'Stripe', 'Paysera', 'Cashu', 'Iyzipay', 'MercadoPago'])) {

                return $redirect_url;
            }

            return $redirect_url;
            //      dd($redirect_url) ;
            return Redirect::away($redirect_url);

        } catch (\Exception $exception) {

            if (!$paymentChannel) {
                return apiResponse2(0, 'gateway_error', trans('api.payment.gateway_error'));
            }

        }
    }

    public function paymentVerify(Request $request, $gateway)
    {
        Log::info('paymentVerify CONTROLLER API: ', $request->all());
        Log::info('gateway NAME API: ', [$gateway]);
        $paymentChannel = PaymentChannel::where('class_name', $gateway)
            ->where('status', 'active')
            ->first();
            
        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            //dd($channelManager);
            $order = $channelManager->verify($request);
            // dd($order);
            // Log::info('channelManager: ', [$order]);
            return $this->paymentOrderAfterVerify($order);

        } catch (\Exception $exception) {
            dd($exception->getMessage());
            // $toastData = [
            //     'title' => trans('cart.fail_purchase'),
            //     'msg' => trans('cart.gateway_error'),
            //     'status' => 'error'
            // ];
            // return redirect('cart')->with(['toast' => $toastData]);
            
            return apiResponse2(0, 'gateway_error', trans('api.payment.gateway_error'));
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

    private function paymentOrderAfterVerify($order)
    {
        
        if (!empty($order)) {
        //    dd($order);
            // Log::info('paymentOrderAfterVerify: ', [$order]);
            if ($order->status == Order::$paying) {
                // Log::info('paymentOrderAfterVerify paying: ', [$order]);
                $this->setPaymentAccounting($order);
                // dd('after setPaymentAccounting');
                $order->update(['status' => Order::$paid]);
                // dd($order->status);
                if ($order->status == Order::$paid) {
                    // dd('before handleLuluPrintJobAfterPayment');
                    $this->handleLuluPrintJobAfterPayment($order);
                }
            } else {
                // Log::info('paymentOrderAfterVerify else: ', [$order]);
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

            // return redirect('/payments/status');
            return apiResponse2(1, 'success', 'Payment Successful');
        } else {
            // dd('Order not found after verification');
            // $toastData = [
            //     'title' => trans('cart.fail_purchase'),
            //     'msg' => trans('cart.gateway_error'),
            //     'status' => 'error'
            // ];

            // return redirect('cart')->with($toastData);
            return apiResponse2(0, 'gateway_error', trans('api.payment.gateway_error'));
        }
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

    public function setPaymentAccounting($order, $type = null)
    {
        // dd('setPaymentAccounting');
        $cashbackAccounting = new CashbackAccounting();
        
        if ($order->is_charge_account) {
            Accounting::charge($order);
            $cashbackAccounting->rechargeWallet($order);
        } else {
            foreach ($order->orderItems as $orderItem) {
                
                if(!is_numeric($order->user_id)){
                    $guestname = User::where('device_id_or_ip_address', $order->user_id)->first();
                    $orderItem->full_name = $guestname->full_name;
                }
                // dd($orderItem, $order->payment_method);
                $sale = Sale::createSales($orderItem, $order->payment_method);
                // dd('after createSales');
                
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
        }
        if(!is_numeric($order->user_id)){
            Cart::emptyWithoutLoginCart($order->user_id);
        }
        else{
            Cart::emptyCart($order->user_id);
        }
        
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

        // dd($book);
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

    public function setPaymentAccountingOLD($order, $type = null)
    {
        if ($order->is_charge_account) {
            Accounting::charge($order);
        } else {
            foreach ($order->orderItems as $orderItem) {
                Log::info('setPaymentAccounting: ', [$orderItem]);
                $sale = Sale::createSales($orderItem, $order->payment_method);

                if (!empty($orderItem->reserve_meeting_id)) {
                    $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
                    $reserveMeeting->update([
                        'sale_id' => $sale->id,
                        'reserved_at' => time()
                    ]);
                }

                if (!empty($orderItem->subscribe_id)) {
                    Accounting::createAccountingForSubscribe($orderItem, $type);
                } elseif (!empty($orderItem->promotion_id)) {
                    Accounting::createAccountingForPromotion($orderItem, $type);
                } else {
                    // webinar and meeting

                    Accounting::createAccounting($orderItem, $type);
                    TicketUser::useTicket($orderItem);
                }
            }
        }

        Cart::emptyCart($order->user_id);
    }

    public function paymentVerifyOLD(Request $request, $gateway)
    {
        $paymentChannel = PaymentChannel::where('class_name', $gateway)
            ->where('status', 'active')
            ->first();

        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            $order = $channelManager->verify($request);
            // print_r($order);die('dfgdfggdf');        

            if (!empty($order)) {
                $orderItem = OrderItem::where('order_id', $order->id)->first();

                $reserveMeeting = null;
                if ($orderItem && $orderItem->reserve_meeting_id) {
                    $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
                }

                if ($order->status == Order::$paying) {
                    $this->setPaymentAccounting($order);

                    $order->update(['status' => Order::$paid]);
                } else {
                    if ($order->type === Order::$meeting) {
                        $reserveMeeting->update(['locked_at' => null]);
                    }
                }

                session()->put($this->order_session_key, $order->id);

                // return redirect('/payments/status');
                return apiResponse2(1, 'success', 'Payment Successful');

            } else {
                $toastData = [
                    'title' => trans('cart.fail_purchase'),
                    'msg' => trans('cart.gateway_error'),
                    'status' => 'error'
                ];

                // return redirect('cart')->with($toastData);
                return apiResponse2(0, 'gateway_error', trans('api.payment.gateway_error'));
            }

        } catch (\Exception $exception) {
            // $toastData = [
            //     'title' => trans('cart.fail_purchase'),
            //     'msg' => trans('cart.gateway_error'),
            //     'status' => 'error'
            // ];
            // print_r($toastData);die;          
            // return redirect('cart')->with(['toast' => $toastData]);
            return apiResponse2(0, 'gateway_error', trans('api.payment.gateway_error'));
        }
    }

    public function payStatus(Request $request)
    {
        $orderId = $request->get('order_id', null);

        if (!empty(session()->get($this->order_session_key, null))) {
            $orderId = session()->get($this->order_session_key, null);
            session()->forget($this->order_session_key);
        }

        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->first();

        if (!empty($order)) {
            $data = [
                'pageTitle' => trans('public.cart_page_title'),
                'order' => $order,
            ];

            return view('web.default.cart.status_pay', $data);
        }

        abort(404);
    }

    public function webChargeGenerator(Request $request)
    {
        return apiResponse2(1, 'generated', trans('api.link.generated'),
            [
                'link' => URL::signedRoute('my_api.web.charge', [apiAuth()->id])
            ]
        );

    }

    public function webChargeRender(User $user)
    {
        Auth::login($user);
        return redirect('/panel/financial/account');

    }


    public function charge(Request $request)
    {
        validateParam($request->all(), [
            'amount' => 'required|numeric',
            'gateway_id' => ['required',
                Rule::exists('payment_channels', 'id')->where('status', 'active')
            ]
            ,
        ]);


        $gateway_id = $request->input('gateway_id');
        $amount = $request->input('amount');


        $userAuth = apiAuth();

        $paymentChannel = PaymentChannel::find($gateway_id);

        $order = Order::create([
            'user_id' => $userAuth->id,
            'status' => Order::$pending,
            'payment_method' => Order::$paymentChannel,
            'is_charge_account' => true,
            'total_amount' => $amount,
            'amount' => $amount,
            'created_at' => time(),
            'type' => Order::$charge,
        ]);


        OrderItem::updateOrCreate([
            'user_id' => $userAuth->id,
            'order_id' => $order->id,
        ], [
            'amount' => $amount,
            'total_amount' => $amount,
            'tax' => 0,
            'tax_price' => 0,
            'commission' => 0,
            'commission_price' => 0,
            'created_at' => time(),
        ]);


        if ($paymentChannel->class_name == 'Razorpay') {
            return $this->echoRozerpayForm($order);
        } else {
            $paymentController = new PaymentsController();

            $paymentRequest = new Request();
            $paymentRequest->merge([
                'gateway_id' => $paymentChannel->id,
                'order_id' => $order->id
            ]);

            return $paymentController->paymentRequest($paymentRequest);
        }
    }

    private function echoRozerpayForm($order)
    {
        $generalSettings = getGeneralSettings();

        echo '<form action="/payments/verify/Razorpay" method="get">
            <input type="hidden" name="order_id" value="' . $order->id . '">

            <script src="/assets/default/js/app.js"></script>
            <script src="https://checkout.razorpay.com/v1/checkout.js"
                    data-key="' . env('RAZORPAY_API_KEY') . '"
                    data-amount="' . (int)($order->total_amount * 100) . '"
                    data-buttontext="product_price"
                    data-description="Rozerpay"
                    data-currency="' . currency() . '"
                    data-image="' . $generalSettings['logo'] . '"
                    data-prefill.name="' . $order->user->full_name . '"
                    data-prefill.email="' . $order->user->email . '"
                    data-theme.color="#43d477">
            </script>

            <style>
                .razorpay-payment-button {
                    opacity: 0;
                    visibility: hidden;
                }
            </style>

            <script>
                $(document).ready(function() {
                    $(".razorpay-payment-button").trigger("click");
                })
            </script>
        </form>';
        return '';
    }

}
