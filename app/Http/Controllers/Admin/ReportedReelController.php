<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reel;

class ReportedReelController extends Controller
{
    public function index(){
        $reels = Reel::where('reports_count','>', 15)->paginate(10);
        $data = [
            'pageTitle' => 'Reported Reels',
            'reels' => $reels
        ];

        return view('admin.reels.index', $data);
    }
}