<?php

use App\Http\Controllers\Api\ReelController;
use App\Http\Controllers\Api\AdminReelController;

 Route::prefix('reels')->group(function () {
        Route::get('/', [ReelController::class, 'index']);
     });

Route::middleware(['auth:api'])->group(function () {
    // Public Reel Routes
    Route::prefix('reels')->group(function () {
        //Route::get('/', [ReelController::class, 'index']);
        Route::post('/', [ReelController::class, 'store']);
        Route::get('/reel/{reel}', [ReelController::class, 'show']);
        Route::delete('/{reel}', [ReelController::class, 'destroy']);
        
        Route::get('/reelgift', [ReelController::class, 'reelgift']);
       
        // Engagement routes
        Route::post('/{reel}/like', [ReelController::class, 'toggleLike']);
        Route::post('/{reel}/share', [ReelController::class, 'sharereel']);
        Route::post('/{reel}/save', [ReelController::class, 'savereel']);
        Route::post('/{reel}/gift', [ReelController::class, 'giftreel']);
        Route::post('/{reel}/comment', [ReelController::class, 'comment']);
        Route::post('/{reel}/review', [ReelController::class, 'review']);
        Route::post('/{reel}/report', [ReelController::class, 'report']);
        Route::post('/{reel}/view', [ReelController::class, 'view']);
    });

    // Admin Reel Routes
    Route::prefix('admin/reels')->middleware('admin')->group(function () {
        Route::get('/reported', [AdminReelController::class, 'reportedReels']);
        Route::get('/reported/{reel}', [AdminReelController::class, 'showReportedReel']);
        Route::post('/{reel}/restore', [AdminReelController::class, 'restore']);
        Route::post('/{reel}/hide', [AdminReelController::class, 'hide']);
        Route::get('/stats', [AdminReelController::class, 'stats']);
    });
});
