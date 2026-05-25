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

    public function allowedMimeTypes($type)
    {
        if ($type === 'image') {
            return ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif', 'image/webp'];
        }
        return parent::allowedMimeTypes($type);
    }
}

