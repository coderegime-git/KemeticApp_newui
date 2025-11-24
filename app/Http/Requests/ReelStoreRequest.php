<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReelStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; // We'll handle authorization in the middleware
    }

    public function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:300',
            'video' => [
                'required',
                'file',
                'mimes:mp4,mov,webm',
                'max:204800', // 200MB
            ],
        ];
    }

    public function messages()
    {
        return [
            'video.max' => 'The video must not be larger than 200MB.',
            'video.mimes' => 'The video must be a file of type: mp4, mov, or webm.',
            'caption.max' => 'The caption must not exceed 300 characters.',
        ];
    }
}
