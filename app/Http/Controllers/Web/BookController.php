<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Add this line
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    //protected $laragonCertPath;
    
    // public function __construct()
    // {
    //     // Laragon certificate path - adjust if different
    //     $this->laragonCertPath = "C:/laragon/etc/ssl/cert.pem";
        
    //     // Alternative paths to check
    //     $alternativePaths = [
    //         "C:/laragon/etc/ssl/cert.pem",
    //         "C:/laragon/etc/ssl/cacert.pem",
    //         "C:/laragon/etc/ssl/certs/ca-bundle.crt",
    //         base_path("cacert.pem"), // If you want to store in your project
    //         storage_path("app/certs/cacert.pem"), // Custom storage path
    //     ];
        
    //     // Find the first existing certificate file
    //     foreach ($alternativePaths as $path) {
    //         if (file_exists($path)) {
    //             $this->laragonCertPath = $path;
    //             break;
    //         }
    //     }
    // }

    public function index(Request $request, $category = null)
    {
        $creator = $request->get('creator', null);
        $search = $request->get('search', null);
        
        $seoSettings = getSeoMetas('books');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('Books');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.books');
        $pageRobot = getPageRobot('books');

        $bookCategories = BookCategory::all();

        $query = Book::orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc');

        $selectedCategory = null;

        if (!empty($category)) {
            $selectedCategory = $bookCategories->where('slug', $category)->first();
            if (!empty($selectedCategory)) {
                $query->where('category_id', $selectedCategory->id);
                $pageTitle .= ' ' . $selectedCategory->title;
                $pageDescription .= ' ' . $selectedCategory->title;
            }
        }

        if (!empty($creator) and is_numeric($creator)) {
            $query->where('creator_id', $creator);
        }

        if (!empty($search)) {
            $query->whereTranslationLike('title', "%$search%");
        }

        $bookCount = $query->count();

        $books = $query->with([
            'categories',
            'creator' => function ($query) {
                $query->select('id', 'full_name', 'avatar', 'role_id', 'role_name');
            }
        ])
            ->withCount(['likes', 'comments', 'share', 'gift', 'savedItems'])
            ->paginate(6);

        $popularBooks = $this->getPopularBooks();
        $popularBook = $popularBooks->first(); // Get the single popular book

        // $token = $this->getLuluAccessTokenUsingCurl();
        $luluPrintJobs = null;

        // if ($token) {
        //     $luluPrintJobs = $this->getLuluPrintJobsUsingCurl('/print-jobs/', 'GET', [], $token); 
        // }

        //$luluPrintJobs = $this->getLuluPrintJobs();

        //dd($luluPrintJobs, $token);

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'books' => $books,
            'bookCount' => $bookCount,
            'bookCategories' => $bookCategories,
            'popularBooks' => $popularBooks,
            'popularBook' => $popularBook,
            'selectedCategory' => $selectedCategory,
            'luluPrintJobs' => $luluPrintJobs,
        ];

        return view(getTemplate() . '.book.index', $data);
    }

    private function getLuluAccessTokenUsingCurl()
    {
        $url = "https://api.lulu.com/auth/realms/glasstree/protocol/openid-connect/token";
        $authorization = "OWY2MDViMTUtNmMzYy00OWU1LTkxOWItODRmNzM0MWEyMjgzOk50cVpOa2N2aE1nNlJpb25FaEVSbWpyZW5EQTJYU3dW";
        // $authorization = "9f605b15-6c3c-49e5-919b-84f7341a2283:NtqZNkcvhMg6RionEhERmjrenDA2XSwV"; // Basic xxxx

        $laragonCertPath = "C:/laragon/etc/ssl/cert.pem";
        $verifyOption = file_exists($laragonCertPath) ? $laragonCertPath : false;


        $curl = curl_init();

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
        // if ($httpCode !== 200) {
        //     throw new \Exception("Failed to get access token: " . ($data['error_description'] ?? 'Unknown error'));
        // }

        //dd($authorization, $response, $httpCode, $error, $data, curl_getinfo($curl), $curl);

        return $data['access_token'] ?? null;
    }

    private function getLuluPrintJobsUsingCurl($endpoint, $method = 'POST', $data = [], $token = null)
    {
        if (!$token) {
            $token = $this->getLuluAccessTokenUsingCurl();
        }
        
        $curl = curl_init();

        $url = env('LULU_BASE_URL', 'https://api.sandbox.lulu.com') . $endpoint;

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

        if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH']) && !empty($data)) {
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

        //dd($curl,$response, $httpCode, $error);

        // if ($error) {
        //     throw new \Exception("API request failed: " . $error);
        // }

        $responseData = json_decode($response, true);

        // if ($httpCode >= 400) {
        //     throw new \Exception("API error: " . 
        //         (isset($responseData['message']) ? $responseData['message'] : 'Unknown error'), 
        //         $httpCode
        //     );
        // }

        return $responseData;
    }

    private function getLuluPrintJobs()
    {
        try {
            // $clientKey = env('LULU_CLIENT_KEY', '9f605b15-6c3c-49e5-919b-84f7341a2283');
            // $clientSecret = env('LULU_CLIENT_SECRET', '20aiFIjqs1ZnCRFBkcbRLIxUUX83ogIp');
            // $baseUrl = env('LULU_BASE_URL', 'https://api.sandbox.lulu.com');

            $clientKey = "9f605b15-6c3c-49e5-919b-84f7341a2283";
            $clientSecret = "20aiFIjqs1ZnCRFBkcbRLIxUUX83ogIp";
            // $baseUrl = "https://api.sandbox.lulu.com";
            $authorization = "OWY2MDViMTUtNmMzYy00OWU1LTkxOWItODRmNzM0MWEyMjgzOk50cVpOa2N2aE1nNlJpb25FaEVSbWpyZW5EQTJYU3dW";
            $baseUrl = "https://api.lulu.com";
            
            $laragonCertPath = "C:/laragon/etc/ssl/cert.pem";
            $verifyOption = file_exists($laragonCertPath) ? $laragonCertPath : false;

            $projectCertPath = storage_path('app/cacert.pem');
            if (!file_exists($projectCertPath)) {
                $this->downloadCertificate($projectCertPath);
            }
            
            $tokenResponse = Http::withOptions([
                'verify' => $verifyOption, // or $projectCertPath
            ])->asForm()->post($baseUrl . '/auth/realms/glasstree/protocol/openid-connect/token', [
                'grant_type' => 'client_credentials',
                'Authorization' => "Basic {$authorization}", 
                'content-type' => 'application/x-www-form-urlencoded',
            ]);
            
            if ($tokenResponse->failed()) {
                \Log::error('Lulu token auth failed', ['response' => $tokenResponse->body()]);
                return collect(); // Return empty collection on error
            }
            
            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'] ?? null;
            
            if (!$accessToken) {
                \Log::error('No access token received from Lulu');
                return collect();
            }
            
            // Now fetch print jobs
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/json',
            ])->get($baseUrl . '/print-jobs/');
            
            if ($response->successful()) {
                $jobs = $response->json();
                
                // Return as collection for easier handling in Blade
                return collect($jobs)->map(function ($job) {
                    return [
                        'id' => $job['id'] ?? null,
                        'status' => $job['status'] ?? 'Unknown',
                        'created' => $job['date_created'] ?? null,
                        'title' => $job['title'] ?? 'Untitled Print Job',
                        'page_count' => $job['page_count'] ?? 0,
                        'total_cost' => $job['total_cost'] ?? 0,
                        'currency' => $job['currency'] ?? 'USD',
                        // Add more fields as needed
                    ];
                });
            }
            
            \Log::error('Failed to fetch Lulu print jobs', ['response' => $response->body()]);
            return collect();
            
        } catch (\Exception $e) {
            \Log::error('Exception fetching Lulu print jobs: ' . $e->getMessage());
            return collect();
        }
    }

    private function downloadCertificate($path)
    {
        $certUrl = 'https://curl.se/ca/cacert.pem';
        $certContent = @file_get_contents($certUrl);
        
        if ($certContent) {
            file_put_contents($path, $certContent);
            return true;
        }
        
        return false;
    }

    public function show($slug)
    {
        if (!empty($slug)) {
            $book = Book::where('slug', $slug)
                ->with([
                    'categories',
                    'creator' => function ($query) {
                        $query->select('id', 'full_name', 'role_id', 'avatar', 'role_name');
                        $query->with('role');
                    },
                    'comments' => function ($query) {
                        //$query->where('status', 'active');
                        //$query->whereNull('reply_id');
                        $query->with([
                            'user' => function ($query) {
                                $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'role_id', 'role_name');
                            },
                            // 'replies' => function ($query) {
                            //     $query->where('status', 'active');
                            //     $query->with([
                            //         'user' => function ($query) {
                            //             $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'role_id', 'role_name');
                            //         }
                            //     ]);
                            // }
                        ]);
                    },
                    'likes',
                    'share',
                    'gift',
                    'savedItems'
                ])
                ->withCount(['likes', 'comments', 'share', 'gift', 'savedItems'])
                ->first();

            if (!empty($book)) {
                $bookCategories = BookCategory::all();
                $popularBooks = $this->getPopularBooks();

                $pageRobot = getPageRobot('books');

                // Calculate engagement metrics
                $totalEngagement = $book->likes_count + $book->comments_count + $book->saved_items_count;
                $ratingDisplay = $book->likes_count > 0 ? number_format(($book->likes_count / max($book->likes_count + 1, 1)) * 5, 1) : '4.98';

                $data = [
                    'pageTitle' => $book->title,
                    'pageDescription' => $book->meta_description ?? $book->title,
                    'pageMetaImage' => $book->getImage(),
                    'bookCategories' => $bookCategories,
                    'popularBooks' => $popularBooks,
                    'pageRobot' => $pageRobot,
                    'book' => $book,
                    'likeCount' => $book->likes_count,
                    'shareCount' => $book->share_count,
                    'giftCount' => $book->gift_count,
                    'commentCount' => $book->comments_count,
                    'savedCount' => $book->saved_items_count,
                    'totalEngagement' => $totalEngagement,
                    'ratingDisplay' => $ratingDisplay,
                    'formattedPrice' => $book->formatted_price,
                    'isFree' => $book->is_free,
                    'bookUrl' => $book->getUrl()
                ];

                return view(getTemplate() . '.book.show', $data);
            }
        }

        abort(404);
    }

    private function getPopularBooks()
    {
        return Book::withCount([
            'likes',
            'comments', 
            'share',
            'gift',
            'savedItems'
        ])
        ->orderByRaw('(
            (likes_count * 2) + 
            (saved_items_count * 3) + 
            (comments_count * 2) + 
            (share_count * 1.5) + 
            (gift_count * 2)
        ) DESC')
        ->limit(1)
        ->get();
    }
}