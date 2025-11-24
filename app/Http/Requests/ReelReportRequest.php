<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReelReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }
}
