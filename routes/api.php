<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\ContactControllerNew;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Web\PagesController;
use App\Http\Controllers\Web\ClassesController;
use App\Http\Controllers\Web\WebinarController;
use App\Http\Controllers\Api\ApiHomeController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/development'], function () {

    Route::get('/', function () {
        return 'api test';
    });

    Route::get('/apihome', 'ApiHomeController@index');

    //Route::get('/home', [ApiHomeController::class, 'index']);

    Route::middleware('api')->group(base_path('routes/api/auth.php'));

    Route::namespace('Web')->group(base_path('routes/api/guest.php'));

    //Route::prefix('panel')->middleware('api.auth')->namespace('Panel')->group(base_path('routes/api/user.php'));
    Route::prefix('panel')->namespace('Panel')->group(base_path('routes/api/user.php'));

    Route::group(['namespace' => 'Config', 'middleware' => []], function () {
        Route::get('/config', ['uses' => 'ConfigController@list']);
        Route::get('/config/register/{type}', ['uses' => 'ConfigController@getRegisterConfig']);
    });

    Route::prefix('instructor')->middleware(['api.auth', 'api.level-access:teacher'])->namespace('Instructor')->group(base_path('routes/api/instructor.php'));

    // Reels Feature Routes
    require base_path('routes/api/reels.php');

    //khushboo 12-11-24
    Route::group(['prefix' => 'pages'], function () {
        Route::get('/{link}', [Controller::class, 'aboutPage']);
    });
   
    Route::post('/contact-us', [ContactControllerNew::class, 'contactUs']);
    //khushboo 12-11-24
});

