<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Webinar;
use App\Models\Product;
use App\Models\Book;
use App\Models\Reel;
use App\User;
use App\Models\ProductMedia;
use App\Models\Translation\BlogTranslation;
use App\Models\Translation\WebinarTranslation;
use App\Models\Translation\ProductTranslation;
use App\Models\Translation\BookTranslation;
use Symfony\Component\Process\Process;

class ShareRedirectController extends Controller
{
    public function launch(Request $request)
    {
        $userAgent = $request->header('User-Agent', '');

        // ✅ CRITICAL: Detect crawler FIRST before any other logic
        $isCrawler = $this->isCrawler($userAgent);

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

        // Default OG data
        $ogData = [
            'title'       => 'Kemetic App',
            'description' => 'Discover ancient wisdom and spiritual teachings',
            'image'       => 'https://kemetic.app/store/1/69f1ea5d674a1.jpeg',
            'url'         => $request->fullUrl(),
            'type'        => 'website',
        ];

        $slug = null;

        // PORTALS / REELS
        if ($page === 'portals') {
            if ($value) {
                $portal = Reel::find($value);
                if ($portal) {
                    $thumbnailPath = null;
                    $reelsStorageDir  = public_path('store/reels');
                    $videosStorageDir = public_path('store/reels/videos');

                    if (!empty($portal->thumbnail_path) && file_exists(public_path($portal->thumbnail_path))) {
                        $thumbnailPath = $portal->thumbnail_path;
                    } elseif (!empty($portal->video_path)) {
                        $videoFilePath = $videosStorageDir . '/' . $portal->video_path;

                        if (file_exists($videoFilePath)) {
                            $thumbFilename = 'thumb_' . $portal->id . '_' . time() . '.jpg';
                            $thumbFullPath = $reelsStorageDir . '/' . $thumbFilename;
                            $thumbRelative = '/store/reels/' . $thumbFilename;

                            if (!is_dir($reelsStorageDir)) {
                                mkdir($reelsStorageDir, 0775, true);
                            }

                            try {
                                $process = new Process([
                                    'ffmpeg', '-i', $videoFilePath,
                                    '-ss', '00:00:01', '-vframes', '1', '-q:v', '2',
                                    $thumbFullPath
                                ]);
                                $process->setTimeout(60);
                                $process->run();

                                if ($process->isSuccessful() && file_exists($thumbFullPath)) {
                                    $portal->thumbnail_path = $thumbRelative;
                                    $portal->save();
                                    $thumbnailPath = $thumbRelative;
                                }
                            } catch (\Exception $e) {
                                \Log::error('FFmpeg error: ' . $e->getMessage());
                            }
                        }
                    }

                    $thumbnailUrl = $thumbnailPath
                        ? $this->toAbsoluteHttps($thumbnailPath)
                        : 'https://kemetic.app/store/1/69f1ea5d674a1.jpeg';

                    $ogData = [
                        'title'       => $portal->title ?? 'Portal Reel',
                        'description' => $portal->caption ?? 'Watch this reel on Kemetic App',
                        'image'       => $thumbnailUrl,
                        'url'         => $request->fullUrl(),
                        'type'        => 'video.other',
                    ];

                    if (!empty($portal->video_path)) {
                        $ogData['video_url'] = 'https://kemetic.app/store/reels/videos/' . $portal->video_path;
                    }
                }
            }
            $webUrl .= "/reels";
        }

        // COURSE / WEBINAR
        elseif ($page === 'course') {
            if ($value) {
                $course             = Webinar::find($value);
                $course_translation = WebinarTranslation::where('webinar_id', $value)->first();

                if ($course) {
                    $slug   = $course->slug;
                    $ogData = [
                        'title'       => $course_translation->title ?? 'Online Course',
                        'description' => strip_tags($course_translation->seo_description ?? 'Join this course on Kemetic App'),
                        'image'       => $this->toAbsoluteHttps($course->thumbnail ?? $course->image_cover ?? ''),
                        'url'         => $request->fullUrl(),
                        'type'        => 'website',
                    ];
                }
            }
            $webUrl .= $slug ? "/course/{$slug}" : "/classes";
        }

        // ARTICLE / BLOG
        elseif ($page === 'article') {
            if ($value) {
                $blog             = Blog::find($value);
                $blog_translation = BlogTranslation::where('blog_id', $value)->first();

                if ($blog) {
                    $slug             = $blog->slug;
                    $meta_description = html_entity_decode(strip_tags($blog_translation->meta_description ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $ogData           = [
                        'title'       => $blog_translation->title ?? 'Article',
                        'description' => $meta_description ?: 'Read this article on Kemetic App',
                        'image'       => $this->getOgImageUrl($blog->image ?? ''),
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
                $product             = Product::find($value);
                $product_translation = ProductTranslation::where('product_id', $value)->first();
                $product_media       = ProductMedia::where('product_id', $value)->first();

                if ($product && $product_media) {
                    $slug     = $product->slug;
                    $imageUrl = ($product->is_cj_product == '1')
                        ? $product_media->path
                        : $this->toAbsoluteHttps($product_media->path);

                    $summary = html_entity_decode(strip_tags($product_translation->summary ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $ogData  = [
                        'title'       => $product_translation->title ?? 'Product',
                        'description' => $summary ?: 'Shop this item on Kemetic App',
                        'image'       => $imageUrl,
                        'url'         => $request->fullUrl(),
                        'type'        => 'website',
                    ];
                }
            }
            $webUrl .= $slug ? "/products/{$slug}" : "/products";
        }

        // SCROLLS / BOOKS
        elseif ($page === 'scrolls') {
            if ($value) {
                $book             = Book::find($value);
                $book_translation = BookTranslation::where('book_id', $value)->first();

                if ($book) {
                    $slug        = $book->slug;
                    $description = html_entity_decode(strip_tags($book_translation->description ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $ogData      = [
                        'title'       => $book_translation->title ?? 'Book',
                        'description' => $description ?: 'Read this book on Kemetic App',
                        'image'       => $this->getOgImageUrl($book->image_cover ?? ''),
                        'url'         => $request->fullUrl(),
                        'type'        => 'website',
                    ];
                }
            }
            $webUrl .= $slug ? "/book/{$slug}" : "/book";
        }

        // PROFILE
        elseif ($page === 'profile') {
            if ($value) {
                $user = User::find($value);
                if ($user) {
                    $avatarUrl = $user->getAvatar(200);
                    $avatarUrl = $this->toAbsoluteHttps($avatarUrl);
                    $ogData    = [
                        'title'       => $user->full_name ?? 'User',
                        'description' => $user->bio ?? 'View this user\'s profile on Kemetic App',
                        'image'       => $avatarUrl,
                        'url'         => $request->fullUrl(),
                        'type'        => 'profile',
                    ];
                }
            }
            $webUrl .= "/users/{$value}/profile";
        }

        // HOME
        elseif ($page === 'home') {
            $webUrl .= "/home";
        }

        // DEFAULT
        else {
            $webUrl .= "/";
        }

        // ✅ Crawlers get OG HTML — no redirect
        if ($isCrawler) {
            \Log::info('Serving OG crawler page for UA: ' . $userAgent);
            return $this->renderCrawlerPage($ogData);
        }

        // Detect device
        $isAndroid = stripos($userAgent, 'android') !== false;
        $isIOS     = (bool) preg_match('/(iPhone|iPad|iPod)/i', $userAgent);

        // Desktop → go to website
        if (!$isAndroid && !$isIOS) {
            return redirect($webUrl);
        }

        $storeUrl = $isAndroid ? $androidStore : $iosStore;

        return $this->renderMobilePage($deeplink, $storeUrl, $ogData);
    }

    /**
     * Force URL to absolute HTTPS
     */
    private function toAbsoluteHttps(string $url): string
    {
        if (empty($url)) {
            return 'https://kemetic.app/store/1/69f1ea5d674a1.jpeg';
        }

        // Already absolute
        if (str_starts_with($url, 'http')) {
            return preg_replace('/^http:\/\//', 'https://', $url);
        }

        // Relative path
        return 'https://kemetic.app/' . ltrim($url, '/');
    }

    /**
     * Detect social media crawlers
     */
    private function isCrawler(string $userAgent): bool
    {
        $crawlers = [
            'WhatsApp',
            'facebookexternalhit',
            'Facebot',
            'Twitterbot',
            'LinkedInBot',
            'Pinterest',
            'Slackbot',
            'TelegramBot',
            'Googlebot',
            'bingbot',
            'Applebot',
            'meta-externalagent',
            'facebookcatalog',
        ];

        foreach ($crawlers as $crawler) {
            if (stripos($userAgent, $crawler) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Render minimal HTML page for crawlers with OG tags
     */
    private function renderCrawlerPage(array $ogData): \Illuminate\Http\Response
    {
        $title       = htmlspecialchars($ogData['title'] ?? 'Kemetic App');
        $description = htmlspecialchars(substr($ogData['description'] ?? '', 0, 200));
        $image       = htmlspecialchars($ogData['image'] ?? '');
        $url         = htmlspecialchars($ogData['url'] ?? '');
        $type        = htmlspecialchars($ogData['type'] ?? 'website');

        // ✅ Force HTTPS on image
        $image = preg_replace('/^http:\/\//', 'https://', $image);

        $imageExt  = strtolower(pathinfo(parse_url($ogData['image'], PHP_URL_PATH), PATHINFO_EXTENSION));
        $imageMime = match($imageExt) {
            'png'  => 'image/png',
            'webp' => 'image/webp',
            'gif'  => 'image/gif',
            default => 'image/jpeg',
        };

        $videoTags = '';
        if (!empty($ogData['video_url'])) {
            $videoUrl  = htmlspecialchars($ogData['video_url']);
            $videoTags = <<<VIDEO
                <meta property="og:video" content="{$videoUrl}">
                <meta property="og:video:secure_url" content="{$videoUrl}">
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

                    <!-- OG Image FIRST — WhatsApp stops parsing early -->
                    <meta property="og:image" content="{$image}">
                    <meta property="og:image:secure_url" content="{$image}">
                    <meta property="og:image:type" content="{$imageMime}">
                    <meta property="og:image:width" content="1200">
                    <meta property="og:image:height" content="630">

                    <title>{$title}</title>
                    <meta name="description" content="{$description}">

                    <meta property="og:type" content="{$type}">
                    <meta property="og:url" content="{$url}">
                    <meta property="og:title" content="{$title}">
                    <meta property="og:description" content="{$description}">
                    <meta property="og:site_name" content="Kemetic App">
                    {$videoTags}

                    <meta name="twitter:card" content="summary_large_image">
                    <meta name="twitter:title" content="{$title}">
                    <meta name="twitter:description" content="{$description}">
                    <meta name="twitter:image" content="{$image}">
                </head>
                <body>
                    <h1>{$title}</h1>
                    <p>{$description}</p>
                    <img src="{$image}" alt="{$title}" style="max-width:100%">
                </body>
            </html>
        HTML;

        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('X-Robots-Tag', 'noindex');
    }

    /**
     * Render page for mobile users with deep link redirect
     */
    private function renderMobilePage(string $deeplink, string $storeUrl, array $ogData): \Illuminate\Http\Response
    {
        $title          = htmlspecialchars($ogData['title'] ?? 'Kemetic App');
        $description    = htmlspecialchars(substr($ogData['description'] ?? '', 0, 200));
        $image          = htmlspecialchars($ogData['image'] ?? '');
        $url            = htmlspecialchars($ogData['url'] ?? '');
        $type           = htmlspecialchars($ogData['type'] ?? 'website');
        $escapedDeeplink = htmlspecialchars($deeplink, ENT_QUOTES, 'UTF-8');
        $escapedStoreUrl = htmlspecialchars($storeUrl, ENT_QUOTES, 'UTF-8');

        $image = preg_replace('/^http:\/\//', 'https://', $image);

        $imageExt  = strtolower(pathinfo(parse_url($ogData['image'], PHP_URL_PATH), PATHINFO_EXTENSION));
        $imageMime = match($imageExt) {
            'png'  => 'image/png',
            'webp' => 'image/webp',
            'gif'  => 'image/gif',
            default => 'image/jpeg',
        };

        $videoTags = '';
        if (!empty($ogData['video_url'])) {
            $videoUrl  = htmlspecialchars($ogData['video_url']);
            $videoTags = <<<VIDEO
                <meta property="og:video" content="{$videoUrl}">
                <meta property="og:video:secure_url" content="{$videoUrl}">
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

                    <meta property="og:image" content="{$image}">
                    <meta property="og:image:secure_url" content="{$image}">
                    <meta property="og:image:type" content="{$imageMime}">
                    <meta property="og:image:width" content="1200">
                    <meta property="og:image:height" content="630">

                    <title>{$title}</title>
                    <meta name="description" content="{$description}">

                    <meta property="og:type" content="{$type}">
                    <meta property="og:url" content="{$url}">
                    <meta property="og:title" content="{$title}">
                    <meta property="og:description" content="{$description}">
                    <meta property="og:site_name" content="Kemetic App">
                    {$videoTags}

                    <meta name="twitter:card" content="summary_large_image">
                    <meta name="twitter:title" content="{$title}">
                    <meta name="twitter:description" content="{$description}">
                    <meta name="twitter:image" content="{$image}">

                    <style>
                        body { margin:0; padding:20px; background:#0a0a0a; color:#fff;
                            font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; text-align:center; }
                        .container { max-width:600px; margin:50px auto; }
                        img { max-width:100%; border-radius:12px; margin:20px 0; }
                        h1 { font-size:24px; margin:20px 0 10px; }
                        p { color:#aaa; line-height:1.5; }
                        .button { display:inline-block; background:#c9a84c; color:#000;
                                padding:14px 30px; border-radius:8px; text-decoration:none;
                                font-weight:bold; margin:20px 0; }
                        .store-links { margin-top:30px; font-size:14px; color:#666; }
                        .store-links a { color:#c9a84c; text-decoration:none; }
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
                        var deeplink  = "{$escapedDeeplink}";
                        var storeUrl  = "{$escapedStoreUrl}";
                        var appOpened = false;

                        function cancelFallback() {
                            appOpened = true;
                            clearTimeout(fallbackTimer);
                        }

                        window.addEventListener('pagehide', cancelFallback, { once: true });
                        window.addEventListener('blur',     cancelFallback, { once: true });
                        document.addEventListener('visibilitychange', function onVis() {
                            if (document.hidden) {
                                appOpened = true;
                                clearTimeout(fallbackTimer);
                                document.removeEventListener('visibilitychange', onVis);
                            }
                        });

                        window.location.href = deeplink;

                        var fallbackTimer = setTimeout(function () {
                            if (!appOpened) window.location.href = storeUrl;
                        }, 1500);
                    })();
                    </script>
                </body>
            </html>
        HTML;

        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }


    private function getOgImage($originalPath): string
    {
        if (empty($originalPath)) {
            return 'https://kemetic.app/store/1/69f1ea5d674a1.jpeg';
        }
        
        // If already a URL, convert to path
        if (str_starts_with($originalPath, 'http')) {
            $relativePath = parse_url($originalPath, PHP_URL_PATH);
            $originalPath = ltrim($relativePath, '/');
        }
        
        $fullPath = public_path($originalPath);
        
        if (!file_exists($fullPath)) {
            return 'https://kemetic.app/store/1/69f1ea5d674a1.jpeg';
        }
        
        // Check file size
        $fileSize = filesize($fullPath);
        if ($fileSize < 300 * 1024) { // Less than 300KB
            return $this->toAbsoluteHttps($originalPath);
        }
        
        // Generate compressed version
        $pathInfo = pathinfo($fullPath);
        $compressedFilename = 'og_' . md5($originalPath) . '.jpg';
        $compressedPath = $pathInfo['dirname'] . '/' . $compressedFilename;
        $compressedRelative = str_replace(public_path(), '', $compressedPath);
        $compressedRelative = ltrim($compressedRelative, '/');
        
        // Generate if doesn't exist or is older than 7 days
        if (!file_exists($compressedPath) || (time() - filemtime($compressedPath) > 604800)) {
            $this->compressImage($fullPath, $compressedPath);
        }
        
        return $this->toAbsoluteHttps($compressedRelative);
    }

    /**
     * Compress image to be under WhatsApp's 300KB limit
     */
    private function compressImage(string $sourcePath, string $destPath): void
    {
        try {
            // Get original dimensions
            $imageInfo = @getimagesize($sourcePath);
            if (!$imageInfo) {
                copy($sourcePath, $destPath);
                return;
            }
            
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $mime = $imageInfo['mime'];
            
            // Create image resource
            switch ($mime) {
                case 'image/jpeg':
                case 'image/jpg':
                    $srcImage = @imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $srcImage = @imagecreatefrompng($sourcePath);
                    // Preserve transparency
                    imagepalettetotruecolor($srcImage);
                    break;
                case 'image/webp':
                    $srcImage = @imagecreatefromwebp($sourcePath);
                    break;
                case 'image/gif':
                    $srcImage = @imagecreatefromgif($sourcePath);
                    break;
                default:
                    copy($sourcePath, $destPath);
                    return;
            }
            
            if (!$srcImage) {
                copy($sourcePath, $destPath);
                return;
            }
            
            // Resize if needed (max 1200px width for OG)
            $maxWidth = 1200;
            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int)($height * ($maxWidth / $width));
                $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                
                // Preserve transparency for PNG
                if ($mime === 'image/png') {
                    imagealphablending($resizedImage, false);
                    imagesavealpha($resizedImage, true);
                }
                
                imagecopyresampled($resizedImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($srcImage);
                $srcImage = $resizedImage;
            }
            
            // Save as JPEG with progressive compression
            $quality = 60; // Start with 60% quality
            imagejpeg($srcImage, $destPath, $quality);
            
            // If still too large, reduce quality further
            while (file_exists($destPath) && filesize($destPath) > 280 * 1024 && $quality > 20) {
                $quality -= 10;
                imagejpeg($srcImage, $destPath, $quality);
            }
            
            imagedestroy($srcImage);
            
        } catch (\Exception $e) {
            \Log::error('Image compression failed: ' . $e->getMessage());
            // Fallback: copy original
            if (!file_exists($destPath)) {
                copy($sourcePath, $destPath);
            }
        }
    }

    private function getOgImageUrl(string $originalPath): string
    {
        // Only process local paths
        if (!str_starts_with($originalPath, '/') && !str_starts_with($originalPath, 'store/')) {
            return $originalPath; // external URL, return as-is
        }

        $relativePath = ltrim(str_replace(url('/'), '', $originalPath), '/');
        $fullPath     = public_path($relativePath);

        if (!file_exists($fullPath)) {
            return 'https://kemetic.app/store/1/69f1ea5d674a1.jpeg';
        }

        // Check if already small enough (under 250KB)
        if (filesize($fullPath) < 250000) {
            return $this->toAbsoluteHttps($originalPath);
        }

        // Generate compressed OG thumbnail
        $ogDir      = public_path('store/og_thumbs');
        $ogFilename = 'og_' . md5($relativePath) . '.jpg';
        $ogFullPath = $ogDir . '/' . $ogFilename;
        $ogUrl      = 'https://kemetic.app/store/og_thumbs/' . $ogFilename;

        // Return cached version if exists
        if (file_exists($ogFullPath)) {
            return $ogUrl;
        }

        // Create directory if needed
        if (!is_dir($ogDir)) {
            mkdir($ogDir, 0775, true);
        }

        try {
            // Use GD (available on most servers)
            $imageInfo = getimagesize($fullPath);
            if (!$imageInfo) return $this->toAbsoluteHttps($originalPath);

            $mime = $imageInfo['mime'];
            $src  = match($mime) {
                'image/png'  => imagecreatefrompng($fullPath),
                'image/webp' => imagecreatefromwebp($fullPath),
                default      => imagecreatefromjpeg($fullPath),
            };

            if (!$src) return $this->toAbsoluteHttps($originalPath);

            // Resize to max 1200x630
            $origW = imagesx($src);
            $origH = imagesy($src);
            $ratio = min(1200 / $origW, 630 / $origH);
            $newW  = (int) ($origW * $ratio);
            $newH  = (int) ($origH * $ratio);

            $dst = imagecreatetruecolor($newW, $newH);

            // White background for PNG transparency
            $white = imagecolorallocate($dst, 255, 255, 255);
            imagefill($dst, 0, 0, $white);

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

            // Save as JPEG at 80% quality (~100-200KB typically)
            imagejpeg($dst, $ogFullPath, 80);

            imagedestroy($src);
            imagedestroy($dst);

            \Log::info('OG thumbnail created: ' . $ogUrl);
            return $ogUrl;

        } catch (\Exception $e) {
            \Log::error('OG thumbnail error: ' . $e->getMessage());
            return $this->toAbsoluteHttps($originalPath);
        }
    }
}