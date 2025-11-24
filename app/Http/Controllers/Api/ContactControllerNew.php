<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\User;
use Illuminate\Http\Request;

class ContactControllerNew extends Controller
{
    
    public function contactUs(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string',
                'email' => 'required|string|email',
                'phone' => 'required|numeric',
                'subject' => 'required|string',
                'message' => 'required|string',
                // 'captcha' => 'required|numeric',
            ]);
        
            $data = $request->all();
            unset($data['_token']);
            $data['created_at'] = time();
        
            Contact::create($data);
        
            $notifyOptions = [
                '[c.u.title]' => $data['subject'],
                '[u.name]' => $data['name'],
                '[time.date]' => dateTimeFormat(time(), 'j M Y H:i'),
                '[c.u.message]' => $data['message'],
            ];
        
            sendNotification('contact_message_submission_for_admin', $notifyOptions, 1);
            sendNotificationToEmail('Your contact message submitted', $notifyOptions, $data['email']);
        
            return response()->json(['status' => 200, 'message' => 'Contact Form Submitted']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $e->validator->errors()
            ], 422);
        }
        
    }

    
}
