<?php

namespace App\Jobs;

use App\Models\Reel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use getID3;

class ProcessReelVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reel;
    public $timeout = 900; // 15 minutes
    public $tries = 3; // Number of times to attempt processing

    public function __construct(Reel $reel)
    {
        $this->reel = $reel;
    }

    public function handle()
    {
        try {
            $videoPath = public_path('store\\reels\\videos\\' . $this->reel->video_path);
            $getID3 = new getID3;
            $fileInfo = $getID3->analyze($videoPath);

            // Get video duration
            $duration = isset($fileInfo['playtime_seconds']) ? (int)$fileInfo['playtime_seconds'] : 0;

            // Generate thumbnail using ffmpeg
            $thumbnailPath = 'thumbnails\\' . pathinfo($this->reel->video_path, PATHINFO_FILENAME) . '.jpg';
            $thumbnailFullPath = public_path('store\\reels\\' . $thumbnailPath);
            
            $this->generateThumbnail($videoPath, $thumbnailFullPath);

            // Process video if needed
            $processedVideoPath = null;
            if ($duration > 30) {
                $processedVideoPath = 'processed\\' . pathinfo($this->reel->video_path, PATHINFO_FILENAME) . '.mp4';
                $outputPath = public_path('store\\reels\\' . $processedVideoPath);
                
                $this->trimVideo($videoPath, $outputPath);
            }

            // Update reel
            $this->reel->update([
                'thumbnail_path' => $thumbnailPath,
                'processed_video_path' => $processedVideoPath,
                'duration' => min($duration, 30),
                'is_processed' => true
            ]);

            // Clean up original if processed
            if ($processedVideoPath && Storage::disk('reels')->exists($processedVideoPath)) {
                Storage::disk('reels')->delete($this->reel->video_path);
                $this->reel->update(['video_path' => $processedVideoPath]);
            }

        } catch (\Exception $e) {
            Log::error('Video processing failed for reel ' . $this->reel->id . ': ' . $e->getMessage());
            $this->fail($e);
        }
    }

    protected function generateThumbnail($videoPath, $outputPath)
    {
        $command = [
            'ffmpeg',
            '-i', $videoPath,
            '-ss', '00:00:01',
            '-vframes', '1',
            '-f', 'image2',
            $outputPath
        ];

        $process = new Process($command);
        $process->setTimeout(60);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Failed to generate thumbnail: ' . $process->getErrorOutput());
        }

        // Optimize thumbnail using Intervention Image
        if (file_exists($outputPath)) {
            $image = Image::make($outputPath);
            $image->resize(720, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($outputPath, 80);
        }
    }

    protected function trimVideo($inputPath, $outputPath)
    {
        $command = [
            'ffmpeg',
            '-i', $inputPath,
            '-t', '30',
            '-c:v', 'libx264',
            '-preset', 'medium',
            '-crf', '23',
            '-c:a', 'aac',
            '-b:a', '128k',
            '-y',
            $outputPath
        ];

        $process = new Process($command);
        $process->setTimeout(600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Failed to process video: ' . $process->getErrorOutput());
        }
    }

    public function failed(\Exception $exception)
    {
        Log::error('Reel processing job failed: ' . $exception->getMessage());
        
        // Update reel status to indicate processing failed
        $this->reel->update([
            'is_processed' => false
        ]);

        // Could add notification logic here
        // NotifyAdmin::dispatch('Reel processing failed: ' . $this->reel->id);
    }
}
