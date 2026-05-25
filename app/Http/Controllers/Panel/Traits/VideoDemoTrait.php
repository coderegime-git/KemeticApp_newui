<?php

namespace App\Http\Controllers\Panel\Traits;

use App\Mixins\BunnyCDN\BunnyVideoStream;
use Illuminate\Http\Request;

trait VideoDemoTrait
{
    private function handleVideoDemoData(Request $request, $data, $name)
    {
        // Upload to Bunny
        if (!empty($data['video_demo_source']) and $data['video_demo_source'] == "secure_host") {

            if (!empty($request->file('video_demo_secure_host_file'))) {
                try {
                    $file = $request->file('video_demo_secure_host_file');
                    $filename = time() . '_' . $file->getClientOriginalName();

                    $userId = auth()->id() ?? 1;
                    $videoPath = public_path('store/' . $userId);

                    if (!file_exists($videoPath)) {
                        mkdir($videoPath, 0777, true);
                    }

                    $file->move($videoPath, $filename);

                    $data['video_demo'] = '/store/' . $userId . '/' . $filename;
                } catch (\Exception $ex) {
                    \Illuminate\Support\Facades\Log::error('Local secure_host upload error: ' . $ex->getMessage());
                }
            }
        } else {

            if (!empty($data['video_demo_source']) and !in_array($data['video_demo_source'], ['upload', 'youtube', 'vimeo', 'external_link'])) {
                $data['video_demo_source'] = 'upload';
            }
        }

        return $data;
    }

}
