<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Webinar;
use App\Models\Product;
use App\Models\Book;
use App\Models\Reel;
use App\Models\ProductMedia;
use App\Models\Translation\BlogTranslation;
use App\Models\Translation\WebinarTranslation;
use App\Models\Translation\ProductTranslation;
use App\Models\Translation\BookTranslation;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ShareRedirectController extends Controller
{
    public function launch(Request $request)
    {
        $page   = $request->query('page', 'home');
        $value  = $request->query('value', '');
        $scheme = 'kemeticapp';

        $deeplink = match($page) {
            'home'  => "{$scheme}://home",
            default => "{$scheme}://{$page}/{$value}",
        };
        
        $androidStore = 'https://play.google.com/store/apps/details?id=com.app.kemeticapp&pcampaignid=web_share';
        $iosStore     = 'https://apps.apple.com/in/app/kemetic-app/id6479200304';
        $webUrl       = 'https://kemetic.app';

        // Initialize OG data with defaults
        $ogData = [
            'title'       => 'Kemetic App',
            'description' => 'Discover ancient wisdom and spiritual teachings',
            'image'       => 'https://kemetic.app/images/default-og-image.jpg',
            'url'         => $request->fullUrl(),
        ];

        // Fetch dynamic OG data and set web URL based on page type
        $slug = null;

        // PORTALS / REELS
        if ($page === 'portals') {
            if ($value) {
                $portal = Reel::find($value);
                if ($portal) {
                    $thumbnailPath = null;
                    
                    // Define storage directory paths
                    $reelsStorageDir = public_path('store/reels');
                    $videosStorageDir = public_path('store/reels/videos');
            
                    // 1. Check if reel has specific thumbnail
                    if (!empty($portal->thumbnail_path) && file_exists(public_path($portal->thumbnail_path))) {
                        $thumbnailPath = $portal->thumbnail_path;
                    } 
                    // 2. Try to generate thumbnail using FFmpeg
                    elseif (!empty($portal->video_path)) {
                        // Construct the full video path - video_path contains just the filename
                        $videoFilePath = $videosStorageDir . '/' . $portal->video_path;
                        
                        \Log::info('Looking for video at: ' . $videoFilePath);
                        
                        // Only proceed if video file exists
                        if (file_exists($videoFilePath)) {
                            $thumbFilename = 'thumb_' . $portal->id . '_' . time() . '.jpg';
                            $thumbFullPath = $reelsStorageDir . '/' . $thumbFilename;
                            $thumbRelative = '/store/reels/' . $thumbFilename;

                            if (!is_dir($reelsStorageDir)) {
                                mkdir($reelsStorageDir, 0775, true);
                            }

                            try {
                                $process = new Process([
                                    'ffmpeg',
                                    '-i', $videoFilePath,
                                    '-ss', '00:00:01',
                                    '-vframes', '1',
                                    '-q:v', '2',
                                    $thumbFullPath
                                ]);
                                $process->setTimeout(60); // 60 seconds timeout
                                $process->run();

                                if ($process->isSuccessful() && file_exists($thumbFullPath)) {
                                    $portal->thumbnail_path = $thumbRelative;
                                    $portal->save();
                                    $thumbnailPath = $thumbRelative;
                                    \Log::info('Thumbnail saved at: ' . $thumbFullPath);
                                } else {
                                    \Log::warning('FFmpeg failed for portal ID: ' . $portal->id);
                                    \Log::warning('Process output: ' . $process->getErrorOutput());
                                }
                                // $ffmpeg = FFMpeg::create([
                                //     'ffmpeg.binaries'  => '/usr/bin/ffmpeg', // Adjust path if needed
                                //     'ffprobe.binaries' => '/usr/bin/ffprobe', // Adjust path if needed
                                //     'timeout'          => 60,
                                //     'ffmpeg.threads'   => 12,
                                // ]);

                                // $video = $ffmpeg->open($videoFilePath);
                                // $frame = $video->frame(TimeCode::fromSeconds(1));
                                // $frame->save($thumbFullPath);

                                // if (file_exists($thumbFullPath)) {
                                //     $portal->thumbnail_path = $thumbRelative;
                                //     $portal->save();
                                //     $thumbnailPath = $thumbRelative;
                                //     \Log::info('Thumbnail saved at: ' . $thumbFullPath);
                                // }
                            } catch (\Exception $e) {
                                \Log::error('FFmpeg process error: ' . $portal->id . ': ' . $e->getMessage());
                                //\Log::error('FFMpeg error for portal ID ' . $portal->id . ': ' . $e->getMessage());
                            }
                        } else {
                            \Log::warning('Video file not found for portal ID: ' . $portal->id . ', path: ' . $videoFilePath);
                        }
                    }
                    
                    // Use found thumbnail or default
                    $thumbnailUrl = $thumbnailPath 
                        ? url($thumbnailPath) 
                        : asset('store/1/default_images/website-logo.png');

                    $ogData = [
                        'title'       => $portal->title ?? 'Portal Reel',
                        'description' => $portal->caption ?? 'Watch this reel on Kemetic App',
                        'image'       => $thumbnailUrl,
                        'url'         => $request->fullUrl(),
                        'type'        => 'video.other',
                    ];
                    
                    // Add video URL if available
                    if (!empty($portal->video_path)) {
                        $ogData['video_url'] = url('/store/reels/videos/' . $portal->video_path);
                    }
                }
            }
            $webUrl .= "/reels";
        }
        
        // COURSE / WEBINAR
        elseif ($page === 'course') {
            if ($value) {
                $course = Webinar::find($value);
                $course_translation = WebinarTranslation::find($value);

                if ($course) {
                    $slug = $course->slug;
                    $ogData = [
                        'title'       => $course_translation->title ?? 'Online Course',
                        'description' => strip_tags($course_translation->seo_description ?? 'Join this course on Kemetic App'),
                        'image'       => url($course->thumbnail) ?? url($course->image_cover) ?? $ogData['image'],
                        'url'         => $request->fullUrl(),
                        'type'        => 'course',
                    ];
                }
            }
            $webUrl .= $slug ? "/course/{$slug}" : "/classes";
        }
        
        // ARTICLE / BLOG
        elseif ($page === 'article') {
            if ($value) {
                $blog = Blog::find($value);
                $blog_translation = BlogTranslation::find($value);

                if ($blog) {
                    $slug = $blog->slug;
                    $ogData = [
                        'title'       => $blog_translation->title ?? 'Article',
                        'description' => $blog_translation->meta_description ?? 'Read this article on Kemetic App',
                        'image'       => url($blog->image) ?? $ogData['image'],
                        'url'         => $request->fullUrl(),
                        'type'        => 'article',
                    ];
                }
            }
            $webUrl .= $slug ? "/blog/{$slug}" : "/blog";
        }
        
        // SHOP / PRODUCT
        elseif ($page === 'shop') {
            if ($value) {
                $product = Product::find($value);
                $product_translation = ProductTranslation::find($value);
                $product_media = ProductMedia::where('product_id', $value)->first();
                if($product->is_cj_product=='1'){
                    $imageUrl = $product_media->path;
                }
                else{
                    $imageUrl = url($product_media->path);
                }
                if ($product) {
                    $slug = $product->slug;
                    $ogData = [
                        'title'       => $product_translation->title ?? 'Product',
                        'description' => $product_translation->summary ?? 'Shop this item on Kemetic App',
                        'image'       => $imageUrl ?? $ogData['image'],
                        'url'         => $request->fullUrl(),
                        'type'        => 'product',
                    ];
                }
            }
            $webUrl .= $slug ? "/products/{$slug}" : "/products";
        }
        
        // SCROLLS / BOOKS
        elseif ($page === 'scrolls') {
            if ($value) {
                $book = Book::find($value);
                $book_translation = BookTranslation::find($value);
                if ($book) {
                    $slug = $book->slug;
                    $ogData = [
                        'title'       => $book_translation->title ?? 'Book',
                        'description' => $book_translation->description ?? 'Read this book on Kemetic App',
                        'image'       => url($book->image_cover) ?? $ogData['image'],
                        'url'         => $request->fullUrl(),
                        'type'        => 'book',
                    ];
                }
            }
            $webUrl .= $slug ? "/book/{$slug}" : "/book";
        }
        
        // HOME
        elseif ($page === 'home') {
            $webUrl .= "/home";
        }
        
        // DEFAULT
        else {
            $webUrl .= "/";
        }

        // Detect device
        $userAgent = $request->header('User-Agent', '');
        $isAndroid = stripos($userAgent, 'android') !== false;
        $isIOS     = (bool) preg_match('/(iPhone|iPad|iPod)/i', $userAgent);

        // Check if request is from a crawler (WhatsApp, Facebook, Twitter)
        $isCrawler = $this->isCrawler($userAgent);

        // For crawlers, return HTML with OG tags without redirect
        if ($isCrawler) {
            return $this->renderCrawlerPage($ogData);
        }

        // Desktop → go to website
        if (!$isAndroid && !$isIOS) {
            return redirect($webUrl);
        }

        $storeUrl = $isAndroid ? $androidStore : $iosStore;

        // Mobile → show page with OG tags AND redirect script
        return $this->renderMobilePage($deeplink, $storeUrl, $ogData);
    }

    /**
     * Check if the request is from a social media crawler
     */
    private function isCrawler($userAgent)
    {
        $crawlers = [
            'WhatsApp', 'facebookexternalhit', 'Facebot', 'Twitterbot',
            'LinkedInBot', 'Pinterest', 'Slackbot', 'TelegramBot'
        ];
        
        foreach ($crawlers as $crawler) {
            if (stripos($userAgent, $crawler) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Render page for crawlers (WhatsApp, Facebook, etc.)
     */
    private function renderCrawlerPage($ogData)
    {
        $title = htmlspecialchars($ogData['title']);
        $description = htmlspecialchars(substr($ogData['description'], 0, 200));
        $image = htmlspecialchars($ogData['image']);
        $url = htmlspecialchars($ogData['url']);
        $type = $ogData['type'] ?? 'website';

        $videoTags = '';
        if (!empty($ogData['video_url'])) {
            $videoUrl = htmlspecialchars($ogData['video_url']);
            $videoTags = <<<VIDEO
    <meta property="og:video" content="{$videoUrl}">
    <meta property="og:video:type" content="video/mp4">
    <meta property="og:video:width" content="1280">
    <meta property="og:video:height" content="720">
VIDEO;
        }

        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>{$title}</title>
    <meta name="title" content="{$title}">
    <meta name="description" content="{$description}">
    
    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="{$type}">
    <meta property="og:url" content="{$url}">
    <meta property="og:title" content="{$title}">
    <meta property="og:description" content="{$description}">
    <meta property="og:image" content="{$image}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    {$videoTags}
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{$title}">
    <meta name="twitter:description" content="{$description}">
    <meta name="twitter:image" content="{$image}">
</head>
<body>
    <h1>{$title}</h1>
    <p>{$description}</p>
    <img src="{$image}" alt="{$title}" style="max-width:100%;">
</body>
</html>
HTML;
        return response($html, 200)->header('Content-Type', 'text/html');
    }

    /**
     * Render page for mobile users with redirect
     */
    private function renderMobilePage($deeplink, $storeUrl, $ogData)
    {
        $title = htmlspecialchars($ogData['title']);
        $description = htmlspecialchars(substr($ogData['description'], 0, 200));
        $image = htmlspecialchars($ogData['image']);
        $url = htmlspecialchars($ogData['url']);
        $type = $ogData['type'] ?? 'website';
        $escapedDeeplink = htmlspecialchars($deeplink, ENT_QUOTES, 'UTF-8');
        $escapedStoreUrl = htmlspecialchars($storeUrl, ENT_QUOTES, 'UTF-8');

        $videoTags = '';
        if (!empty($ogData['video_url'])) {
            $videoUrl = htmlspecialchars($ogData['video_url']);
            $videoTags = <<<VIDEO
    <meta property="og:video" content="{$videoUrl}">
    <meta property="og:video:type" content="video/mp4">
    <meta property="og:video:width" content="1280">
    <meta property="og:video:height" content="720">
VIDEO;
        }

        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>{$title}</title>
    <meta name="title" content="{$title}">
    <meta name="description" content="{$description}">
    
    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="{$type}">
    <meta property="og:url" content="{$url}">
    <meta property="og:title" content="{$title}">
    <meta property="og:description" content="{$description}">
    <meta property="og:image" content="{$image}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    {$videoTags}
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{$title}">
    <meta name="twitter:description" content="{$description}">
    <meta name="twitter:image" content="{$image}">
    
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #0a0a0a;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
        }
        img {
            max-width: 100%;
            border-radius: 12px;
            margin: 20px 0;
        }
        h1 {
            font-size: 24px;
            margin: 20px 0 10px;
        }
        p {
            color: #aaa;
            line-height: 1.5;
        }
        .button {
            display: inline-block;
            background: #c9a84c;
            color: #000;
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px 0;
        }
        .store-links {
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
        .store-links a {
            color: #c9a84c;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{$image}" alt="{$title}">
        <h1>{$title}</h1>
        <p>{$description}</p>
        <a href="{$escapedDeeplink}" class="button">Open in Kemetic App</a>
        <div class="store-links">
            Don't have the app?<br>
            <a href="{$escapedStoreUrl}">Download from Store</a>
        </div>
    </div>
    
    <script>
    (function () {
        var deeplink = "{$escapedDeeplink}";
        var storeUrl = "{$escapedStoreUrl}";
        var appOpened = false;

        function cancelFallback() {
            appOpened = true;
            clearTimeout(fallbackTimer);
        }

        window.addEventListener('pagehide', cancelFallback, { once: true });
        window.addEventListener('blur',     cancelFallback, { once: true });
        document.addEventListener('visibilitychange', function onVisChange() {
            if (document.hidden) {
                appOpened = true;
                clearTimeout(fallbackTimer);
                document.removeEventListener('visibilitychange', onVisChange);
            }
        });

        // Auto-redirect to app
        window.location.href = deeplink;

        // Fallback to store if app not opened
        var fallbackTimer = setTimeout(function () {
            if (!appOpened) {
                window.location.href = storeUrl;
            }
        }, 1500);
    })();
    </script>
</body>
</html>
HTML;
        return response($html, 200)->header('Content-Type', 'text/html');
    }
}