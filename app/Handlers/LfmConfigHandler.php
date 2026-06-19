<?php

namespace App\Handlers;

use UniSharp\LaravelFilemanager\Handlers\ConfigHandler;

class LfmConfigHandler extends ConfigHandler
{
    public function userField()
    {
        $user = auth()->user();
        return $user->id;
    }

    public function allowedMimeTypes($type = null)
    {
        $type = $type ?? $this->input('type');

        if ($type === 'image') {
            return [
                'image/jpeg',
                'image/pjpeg', 
                'image/png',
                'image/gif',
                'image/webp',
            ];
        }

        // 'file' type - all allowed
        return config('lfm.folder_categories.file.valid_mime', []);
    }

    // Filter what gets LISTED/DISPLAYED
    public function isAllowedMimeType($mimeType, $type = null)
    {
        $type = $type ?? $this->input('type');

        if ($type === 'image') {
            $allowedMimes = [
                'image/jpeg',
                'image/pjpeg',
                'image/png', 
                'image/gif',
                'image/webp',
            ];
            return in_array($mimeType, $allowedMimes);
        }

        return true;
    }
}

