<?php

namespace App\Http\Controllers\Panel;

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

        return view(getTemplate() . '.panel.book.lists', $data);
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
        return view(getTemplate() . '.panel.book.create', $data);
    }

    public function store(Request $request)
    {
        //$this->authorize('admin_book_create');

        
        $this->validate($request, [
            'locale' => 'required',
            'title' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'image_cover' => 'required|string',
            'image_path' => 'required|string',
            'type' => 'required|string',
            'price' => 'nullable',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);
       
        $data = $request->all();

        $pdfService = new PdfResizerService();
        
        $pdfurl = url($data['image_path']);

        $interior = $pdfService->resizeForLulu(
            $pdfurl, // interior PDF
            false                // no full bleed
        );

        $interiorPdfPath = str_replace(public_path(), '', $interior['local_path']);
        $pageCount = $interior['page_count'];

        $cover = $pdfService->generateCoverFromPdf(
            $pdfurl, // cover PDF
            $pageCount
        );

        $coverPdfPath = str_replace(public_path(), '', $cover['local_path']);

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

        return redirect('panel/book/')->with('success', 'Book created successfully.');
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

        
        return view(getTemplate() . '.panel.book.create', $data);
    }

    public function update(Request $request, $id)
    {
        //$this->authorize('admin_book_edit');

        $this->validate($request, [
            'locale' => 'required',
            'title' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'image_cover' => 'required|string',
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

        $interior = $pdfService->resizeForLulu(
            $pdfurl, // interior PDF
            false                // no full bleed
        );

        $interiorPdfPath = str_replace(public_path(), '', $interior['local_path']);
        $pageCount = $interior['page_count'];

        $cover = $pdfService->generateCoverFromPdf(
            $pdfurl, // cover PDF
            $pageCount
        );

        $coverPdfPath = str_replace(public_path(), '', $cover['local_path']);

        // Update the book
        $book->update([
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
            'price' => $data['price'] ?? null,
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

        return redirect('panel/book/')->with('success', 'Book updated successfully.');
    }

    public function delete($id)
    {
        //$this->authorize('admin_book_delete');

        $book = Book::findOrFail($id);
        $book->delete();

        return redirect('panel/book/')->with('success', 'Book deleted successfully.');
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
}