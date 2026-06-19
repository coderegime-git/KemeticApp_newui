<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use App\User;
use App\Models\Role;
use App\Models\BookTranslation;
use App\Services\PdfResizerService;
use Illuminate\Http\Request;

class BookController extends Controller
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

    public function index(Request $request)
    {
        //$this->authorize('admin_book_list');

        // $books = Book::with('translation')
        // ->orderBy('created_at', 'desc')
        // ->paginate(10);

        $query = Book::query();

        $books = $this->filters($query, $request)
        ->with(['categories', 'creator' => function ($query) {
            $query->select('id', 'full_name');
        }])
        ->withCount('comments')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        $bookCategories = BookCategory::all();
        $adminRoleIds = Role::where('is_admin', true)->pluck('id')->toArray();
        $authors = User::select('id', 'full_name', 'role_id')->whereIn('role_id', $adminRoleIds)->get();

        $data = [
            'pageTitle' => 'Scrolls',
            'books' => $books,
            'bookCategories' => $bookCategories,
            'authors' => $authors,
        ];

        return view('admin.book.lists', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $title = $request->get('title', null);
        $category_id = $request->get('category_id', null);
        $author_id = $request->get('author_id', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');


        if (!empty($title)) {
            $query->whereTranslationLike('title', '%' . $title . '%');
        }

        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }

        if (!empty($author_id)) {
            $query->where('creator_id', $author_id);
        }

        return $query;
    }

    public function create()
    {
        //$this->authorize('admin_book_create');

        $categories = BookCategory::all();

        $data = [
            'pageTitle' => 'Create New Scrolls',
            'categories' => $categories
        ];

        return view('admin.book.create', $data);
    }

    public function store(Request $request)
    {
        //$this->authorize('admin_book_create');

        $this->validate($request, [
            'locale' => 'required',
            'title' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'image_cover' => 'required|string',
            'cover_pdf' => 'required|string',
            'image_path' => 'required|string',
            'type' => 'required|string',
            'price' => 'nullable|integer',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        $data = $request->all();

        $pdfService = new PdfResizerService();
        
        $pdfurl = url($data['image_path']);
        $coverpdfurl = url($data['cover_pdf']);

        if($data['type'] == 'Print')
        {
           $covercheck = $pdfService->resizeForLulu(
                $coverpdfurl, // interior PDF
                false                // no full bleed
            );
            
            $coverpageCount = $covercheck['page_count'];

            if ($coverpageCount > 1) {
                $toastData = [
                    'title' => 'Cover PDF Upload Error',
                    'msg' => 'Cover PDF must be a single page only. Your file contains ' . $coverpageCount . ' pages.',
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData])->withInput();
            }

            $interior = $pdfService->resizeForLulu(
                $pdfurl, // interior PDF
                false                // no full bleed
            );

            $interiorPdfPath = str_replace(public_path(), '', $interior['local_path']);
            $pageCount = $interior['page_count'];
            
            $token = $this->getLuluAccessTokenUsingCurl();
            $podPackageId = "0600X0900BWSTDPB060UW444MXX"; // default

            $interiorPublicUrl = url(str_replace('\\', '/', ltrim($interiorPdfPath, '/\\')));

            $interiorValidation = $this->validateLuluFile('interior', $interiorPublicUrl, $token);
            
            if (!empty($interiorValidation['errors'])) {
                $toastData = [
                    'title' => 'Interior PDF Validation Error',
                    'msg' => $this->formatLuluValidationErrors($interiorValidation['errors']),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData])->withInput();
            }

            $dimensions = $this->getLuluCoverDimensionsFromApi($podPackageId, $pageCount, $token);

            if (!isset($dimensions['width']) || !isset($dimensions['height'])) {
                $toastData = [
                    'title' => 'Dimension Error',
                    'msg' => 'Could not calculate cover dimensions from Lulu. Please check your interior PDF page count and try again.',
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData])->withInput();
            }

            $cover = $pdfService->generateCoverFromDimensions(
                $coverpdfurl,
                (float) $dimensions['width'],
                (float) $dimensions['height']
            );

            $coverPdfPath = str_replace(public_path(), '', $cover['local_path']);
            $coverPublicUrl = url(str_replace('\\', '/', ltrim($coverPdfPath, '/\\')));

            $coverValidation = $this->validateLuluFile('cover', $coverPublicUrl, $token, $podPackageId, $pageCount);
            // dd($coverValidation);
            if (!empty($coverValidation['errors'])) {
                $toastData = [
                    'title' => 'Cover PDF Validation Error',
                    'msg' =>  $this->formatLuluValidationErrors($coverValidation['errors']),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData])->withInput();
            }
        }
        else
        {
            // For non-print books, just use the original PDF path
            $interiorPdfPath = $data['image_path'];
            $coverPdfPath = $data['cover_pdf'];
            $pageCount = 0;
        }

        // Create the book
        $book = Book::create([
            'creator_id' => !empty($data['author_id']) ? $data['author_id'] : auth()->id(),
            'category_id' => $data['category_id'],
            'slug' => Book::makeSlug($data['title']),
            // 'image_cover' => $data['image_cover'],
            // 'url' => $data['image_path'],
            'image_cover' => $data['image_cover'],     // ✅ Lulu cover PDF
            'url'         => $interiorPdfPath,  // ✅ Lulu interior PDF
            'cover_pdf'   => $coverPdfPath,  
            'page_count' => $pageCount,
            'price' => $data['price'] ?? null,
            'print_price' => $data['print_price'] ?? null,
            'shipping_price' => $data['shipping_price'] ?? null,
            'platform_price' => $data['platform_price'] ?? null,
            'book_price' => $data['book_price'] ?? null,
            'type' => $data['type'],
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        if ($book) {

            BookTranslation::updateOrCreate([
                'book_id' => $book->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
            ]);
        }

        return redirect(getAdminPanelUrl().'/book')->with('success', 'Book created successfully.');
    }

    public function edit($id)
    {
        //$this->authorize('admin_book_edit');

        $book = Book::with('translations')->findOrFail($id);
        $categories = BookCategory::all();

        $data = [
            'pageTitle' => 'Edit Scrolls',
            'categories' => $categories,
            'book' => $book
        ];

        return view('admin.book.create', $data);
    }

    public function update(Request $request, $id)
    {
        //$this->authorize('admin_book_edit');

        $this->validate($request, [
            'locale' => 'required',
            'title' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'image_cover' => 'required|string',
            'cover_pdf' => 'required|string',
            'image_path' => 'required|string',
            'type' => 'required|string',
            'price' => 'nullable',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        $data = $request->all();
        $book = Book::findOrFail($id);

        $pdfService = new PdfResizerService();
        
        $pdfurl = url($data['image_path']);
        $coverpdfurl = url($data['cover_pdf']);

        if($data['type'] == 'Print')
        {
            $covercheck = $pdfService->resizeForLulu(
                $coverpdfurl, // interior PDF
                false                // no full bleed
            );
            
            $coverpageCount = $covercheck['page_count'];

            if ($coverpageCount > 1) {
                $toastData = [
                    'title' => 'Cover PDF Upload Error',
                    'msg' => 'Cover PDF must be a single page only. Your file contains ' . $coverpageCount . ' pages.',
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData])->withInput();
            }

            $interior = $pdfService->resizeForLulu(
                $pdfurl, // interior PDF
                false                // no full bleed
            );

            $interiorPdfPath = str_replace(public_path(), '', $interior['local_path']);
            $pageCount = $interior['page_count'];
            
            $token = $this->getLuluAccessTokenUsingCurl();
            $podPackageId = "0600X0900BWSTDPB060UW444MXX"; // default

            $interiorPublicUrl = url(str_replace('\\', '/', ltrim($interiorPdfPath, '/\\')));

            $interiorValidation = $this->validateLuluFile('interior', $interiorPublicUrl, $token);
            
            if (!empty($interiorValidation['errors'])) {
                $toastData = [
                    'title' => 'Interior PDF Validation Error',
                    'msg' => $this->formatLuluValidationErrors($interiorValidation['errors']),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData])->withInput();
            }

            $dimensions = $this->getLuluCoverDimensionsFromApi($podPackageId, $pageCount, $token);

            if (!isset($dimensions['width']) || !isset($dimensions['height'])) {
                $toastData = [
                    'title' => 'Dimension Error',
                    'msg' => 'Could not calculate cover dimensions from Lulu. Please check your interior PDF page count and try again.',
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData])->withInput();
            }

            $cover = $pdfService->generateCoverFromDimensions(
                $coverpdfurl,
                (float) $dimensions['width'],
                (float) $dimensions['height']
            );

            $coverPdfPath = str_replace(public_path(), '', $cover['local_path']);
            $coverPublicUrl = url(str_replace('\\', '/', ltrim($coverPdfPath, '/\\')));

            $coverValidation = $this->validateLuluFile('cover', $coverPublicUrl, $token, $podPackageId, $pageCount);
            // dd($coverValidation);
            if (!empty($coverValidation['errors'])) {
                $toastData = [
                    'title' => 'Cover PDF Validation Error',
                    'msg' =>  $this->formatLuluValidationErrors($coverValidation['errors']),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData])->withInput();
            }
        }
        else
        {
            // For non-print books, just use the original PDF path
            $interiorPdfPath = $data['image_path'];
            $coverPdfPath = $data['cover_pdf'];
            $pageCount = 0;
        }

        // Update the book
        $book->update([
            // 'creator_id' => !empty($data['author_id']) ? $data['author_id'] : auth()->id(),
            'category_id' => $data['category_id'],
            'slug' => Book::makeSlug($data['title']),
            // 'image_cover' => $data['image_cover'],
            // 'url' => $data['image_path'],
            'image_cover' => $data['image_cover'],     // ✅ Lulu cover PDF
            'url'         => $interiorPdfPath,  // ✅ Lulu interior PDF
            'cover_pdf'   => $coverPdfPath,  
            'page_count' => $pageCount,
            'price' => $data['price'] ?? null,
            'print_price' => $data['print_price'] ?? null,
            'shipping_price' => $data['shipping_price'] ?? null,
            'platform_price' => $data['platform_price'] ?? null,
            'book_price' => $data['book_price'] ?? null,
            'type' => $data['type'],
            'updated_at' => time(),
        ]);

        BookTranslation::updateOrCreate([
            'book_id' => $book->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'description' => $data['description'],
            'content' => $data['content'],
        ]);

        return redirect(getAdminPanelUrl().'/book')->with('success', 'Book updated successfully.');
    }

    public function delete($id)
    {
        //$this->authorize('admin_book_delete');

        $book = Book::findOrFail($id);
        $book->delete();

        return redirect(getAdminPanelUrl().'/book')->with('success', 'Book deleted successfully.');
    }

    private function formatLuluValidationErrors(array $errors): string
    {
        if (empty($errors)) {
            return 'An unknown validation error occurred.';
        }

        $messages = [];

        foreach ($errors as $error) {
            // Lulu errors can come as strings or as objects with a 'message' key
            if (is_string($error)) {
                $messages[] = $error;
            } elseif (is_array($error)) {
                if (!empty($error['message'])) {
                    $messages[] = $error['message'];
                } elseif (!empty($error['detail'])) {
                    $messages[] = $error['detail'];
                } elseif (!empty($error['field'])) {
                    $messages[] = $error['field'] . ': ' . ($error['description'] ?? 'Invalid value');
                } else {
                    $messages[] = json_encode($error);
                }
            }
        }

        return implode(' | ', array_filter($messages)) ?: 'Validation failed with unknown errors.';
    }

    public function getLuluAccessTokenUsingCurl()
    {
        
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

        return $data['access_token'] ?? null;
    }

    public function getLuluPriceUsingCurl(Request $request, $method = 'POST', $token = null)
    {
        if (!$token) {
            $token = $this->getLuluAccessTokenUsingCurl();
        }

        $lineItems[] = [
            'page_count' => $request->pages,
            'pod_package_id' => '0600X0900BWSTDPB060UW444MXX', // Default package
            'quantity' => '1'
        ];

        $data = [
            'line_items' => $lineItems,
            'shipping_address' => [
                'city' => 'washington',
                'country_code' => 'US',
                'postcode' => '20540',
                'state_code' => 'DC',
                // 'street1' => ($addressData['house_no'] ?? '') . ' ' . ($addressData['address'] ?? ''),
                'street1' => '1600 Pennsylvania Avenue NW',
                'phone_number' => '+1234567890',
            ],
            'shipping_option' => 'MAIL' // Standard shipping
        ];


        $curl = curl_init();
        
        $url = "https://api.lulu.com/print-job-cost-calculations/";
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '. $token,
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

        //dd($response);
        
        // Enhanced error logging
        if ($error) {
            $errorNo = curl_errno($curl);
            $errorInfo = [
                'error_no' => $errorNo,
                'error_msg' => $error,
                'endpoint' => $endpoint,
                'cert_path' => $this->laragonCertPath,
                'cert_exists' => file_exists($this->laragonCertPath) ? 'Yes' : 'No',
                'url' => $url,
            ];
            \Log::error('Lulu API cURL Error', $errorInfo);
        }
        
        curl_close($curl);

        $responseData = json_decode($response, true);

         if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('Lulu API Invalid JSON Response', [
                'response' => $response,
                'json_error' => json_last_error_msg()
            ]);
            
            return [
                'success' => false,
                'message' => 'Invalid response from Lulu API',
                'print_price' => 0
            ];
        }
        
        // Check if we got the expected data structure
        if (isset($responseData['line_item_costs'][0]['total_cost_incl_tax'])) {
            $printPrice = (float) $responseData['line_item_costs'][0]['total_cost_incl_tax'];
            
            return [
                'success' => true,
                'print_price' => $printPrice,
                'raw_response' => $responseData // Optional: include raw response for debugging
            ];
        } else {
            \Log::error('Lulu API Unexpected Response Structure', [
                'response_data' => $responseData
            ]);
            
            return [
                'success' => false,
                'message' => 'Unexpected response structure from Lulu API',
                'print_price' => 0,
                'raw_response' => $responseData
            ];
        }
       
        //return $responseData;
    }

    private function validateLuluFile($type, $sourceUrl, $token, $podPackageId = null, $pageCount = null)
    {
        // ── STEP 1: POST to start validation, get the ID ──────────────────────
        $postUrl = $type == 'interior'
            ? 'https://api.lulu.com/validate-interior/'
            : 'https://api.lulu.com/validate-cover/';

        $postData = ['source_url' => $sourceUrl];
        if ($type == 'cover') {
            $postData['pod_package_id']       = $podPackageId;
            $postData['interior_page_count']  = $pageCount;
        }

        $postOptions = [
            CURLOPT_URL            => $postUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($postData),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $token,
                'Cache-Control: no-cache',
                'Content-Type: application/json'
            ],
        ];

        $this->applySslOptions($postOptions);

        $curl = curl_init();
        curl_setopt_array($curl, $postOptions);
        $postResponse = curl_exec($curl);

        if (curl_error($curl)) {
            $error = curl_error($curl);
            \Log::error("cURL POST Error Lulu Validation ($type): " . $error);
            curl_close($curl);
            return ['errors' => [['message' => $error]]];
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $postData = json_decode($postResponse, true);

        \Log::info("Lulu Validation POST ($type) - HTTP $httpCode", ['response' => $postData]);

        // ── STEP 2: Extract validation ID from POST response ──────────────────
        $validationId = $postData['id'] ?? null;

        if (!$validationId) {
            \Log::error("Lulu Validation: No ID returned for $type", ['response' => $postData]);
            return ['errors' => [['message' => 'Validation ID not returned from Lulu API.']]];
        }

        // ── STEP 3: GET poll until status is complete ─────────────────────────
        $getUrl = $type == 'interior'
            ? "https://api.lulu.com/validate-interior/{$validationId}/"
            : "https://api.lulu.com/validate-cover/{$validationId}/";

        $maxAttempts  = 20;  // 20 x 3s = 60 seconds max wait
        $sleepSeconds = 3;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {

            sleep($sleepSeconds);

            $getOptions = [
                CURLOPT_URL            => $getUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'GET',
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $token,
                    'Cache-Control: no-cache',
                ],
            ];

            $this->applySslOptions($getOptions);

            $curl = curl_init();
            curl_setopt_array($curl, $getOptions);
            $getResponse = curl_exec($curl);

            if (curl_error($curl)) {
                \Log::error("cURL GET Error Lulu Validation ($type, attempt $attempt): " . curl_error($curl));
                curl_close($curl);
                continue; // retry on network error
            }
            curl_close($curl);

            $pollData = json_decode($getResponse, true);
            $status   = $pollData['status'] ?? '';

            \Log::info("Lulu Validation GET ($type, attempt $attempt)", [
                'validation_id' => $validationId,
                'status'        => $status,
                'data'          => $pollData,
            ]);

            // Still processing — keep polling
            if (in_array($status, ['VALIDATING', 'NORMALIZING', 'PENDING'])) {
                continue;
            }

            // ── Validation finished ───────────────────────────────────────────
            // If errors array is non-empty, caller will show toast
            return $pollData;
        }

        // ── Timed out ─────────────────────────────────────────────────────────
        \Log::error("Lulu Validation timed out after {$maxAttempts} attempts ($type)", [
            'validation_id' => $validationId,
        ]);

        return ['errors' => [['message' => 'Lulu ' . $type . ' validation timed out. Please try again.']]];
    }

    /**
     * Apply SSL options to a cURL options array.
     */
    private function applySslOptions(array &$options): void
    {
        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            $options[CURLOPT_CAINFO]         = $this->laragonCertPath;
            $options[CURLOPT_CAPATH]         = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            \Log::warning('SSL certificate verification disabled. Certificate file not found.');
        }
    }

    private function getLuluCoverDimensionsFromApi($podPackageId, $pageCount, $token)
    {
        $curl = curl_init();
        $url = 'https://api.lulu.com/cover-dimensions/';

        $data = [
            'pod_package_id' => $podPackageId,
            'interior_page_count' => $pageCount
        ];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Cache-Control: no-cache',
                'Content-Type: application/json'
            ],
        ];

        if ($this->laragonCertPath && file_exists($this->laragonCertPath)) {
            $options[CURLOPT_CAINFO] = $this->laragonCertPath;
            $options[CURLOPT_CAPATH] = dirname($this->laragonCertPath);
            $options[CURLOPT_SSL_VERIFYPEER] = true;
            $options[CURLOPT_SSL_VERIFYHOST] = 2;
        } else {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        if (curl_error($curl)) {
            \Log::error("cURL Error Lulu Cover Dimensions: " . curl_error($curl));
            return null;
        }
        curl_close($curl);

        return json_decode($response, true);
    }
}