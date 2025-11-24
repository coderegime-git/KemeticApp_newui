<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('web.default.pages.home');
    }

    public function handlePost(Request $request)
    {
        return response()->json(['message' => 'POST request received']);
    }
}
