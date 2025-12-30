<?php

use App\Mixins\Financial\MultiCurrency;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Panel\ProductController;
use App\Http\Controllers\Panel\Store\ProductFilterController;
use App\Http\Controllers\Api\Panel\CartController;

Route::group([], function () {

    Route::get('/getCourse', [ProductController::class, 'getCourses']);
    Route::get('/specifications/{id}', [ProductController::class, 'getSpecifications']);
    Route::get('/parameters/{id}', [ProductController::class, 'getSpecificationParameters']);
    Route::get('/filterByCategoryId/{id}', [ProductFilterController::class, 'getByCategoryId']);
    Route::get('/getCity', [CartController::class, 'getCity']);
    Route::get('/getCountry', [CartController::class, 'getCountry']);
    Route::get('/home', ['uses' => 'WebinarController@home']);
   
    Route::group(['prefix' => 'courses'], function () {

        Route::get('/', ['uses' => 'WebinarController@index']);
        Route::get('/home', ['uses' => 'WebinarController@home']);
        Route::get('/new', ['uses' => 'WebinarController@getWebinarsCombined']);
        Route::get('/categoriesnew', ['uses' => 'WebinarController@categoriesnew']);
        Route::get('/{id}', ['uses' => 'WebinarController@show']);
        Route::get('/{id}/content', ['uses' => 'WebinarController@content']);
        Route::get('/{id}/quizzes', ['uses' => 'WebinarContentController@quizzes']);
        Route::get('/{id}/certificates', ['uses' => 'WebinarContentController@certificates']);


        Route::get('reports/reasons', ['uses' => 'ReportsController@index']);


        Route::post('/{id}/report', ['uses' => 'WebinarController@report', 'middleware' => 'api.auth']);

        Route::post('/{webinar_id}/toggle', ['uses' => 'WebinarController@learningStatus', 'middleware' => 'api.auth']);

        Route::post('/direct-payment', 'WebinarController@directPayment');

    });

    Route::get('certificate_validation', ['uses' => 'CertificatesController@checkValidate', 'middleware' => 'api.request.type']);

    Route::get('featured-courses', ['uses' => 'FeatureWebinarController@index']);
    Route::get('categories', ['uses' => 'CategoriesController@index']);
    Route::get('categories/{id}/webinars', ['uses' => 'CategoriesController@categoryWebinar']);
    Route::get('trend-categories', ['uses' => 'CategoriesController@trendCategory']);
    Route::get('search', ['uses' => 'SearchController@list']);

    /******  Users ******/
    Route::group(['prefix' => 'providers'], function () {
        Route::get('instructors', ['uses' => 'UserController@instructors']);
        Route::get('organizations', ['uses' => 'UserController@organizations']);
        Route::get('consultations', ['uses' => 'UserController@consultations']);

    }); 

    /******  Meetings ******/
    Route::post('meetings/reserve', ['uses' => 'MeetingsController@reserve', 'middleware' => ['api.auth', 'api.request.type']]);
    Route::get('users/{id}/meetings', ['uses' => 'UserController@availableTimes']);


    Route::get('users/{id}/profile', ['uses' => 'UserController@profile']);
    Route::post('users/{id}/send-message', 'UserController@sendMessage');
    Route::get('users/{id}/dashboard', 'UserController@dashboard');

    Route::get('/files/{file_id}/download', ['uses' => 'FilesController@download']);

    Route::prefix('fmd')->group(function () {
        Route::get('/adreels', 'AdReelController@adreels');
        Route::get('/plans', 'AdReelController@plans');
        Route::post('/add',  ['uses' => 'AdReelController@store','middleware' => 'api.auth']);
        Route::post('/purchase', ['uses' => 'AdReelController@purchase','middleware' => 'api.auth']);
    });

    Route::group(['prefix' => 'blogs'], function () {

        Route::get('/', ['uses' => 'BlogController@index']);
        // Route::get('/', ['uses' => 'BlogController@index','middleware' => 'api.auth']);
        Route::get('/categories', ['uses' => 'BlogCategoryController@index']);
        Route::get('/{id}', ['uses' => 'BlogController@show']);

        Route::post('/{blog}/like', ['uses' => 'BlogController@bloglike','middleware' => 'api.auth']);
        Route::post('/{blog}/share', ['uses' => 'BlogController@blogshare','middleware' => 'api.auth']);
        Route::post('/{blog}/gift', ['uses' => 'BlogController@bloggift','middleware' => 'api.auth']);
        Route::post('/{blog}/comment', ['uses' => 'BlogController@blogcomment','middleware' => 'api.auth']);

        //Route::post('/{blog}/like', ['uses' => 'BlogController@bloglike']);
        // Route::post('/{blog}/share', ['uses' => 'BlogController@blogshare']);
        // Route::post('/{blog}/gift', ['uses' => 'BlogController@bloggift']);
        // Route::post('/{blog}/comment', ['uses' => 'BlogController@blogcomment']);

    });

    Route::group(['prefix' => 'books'], function () {

        Route::get('/', ['uses' => 'BookController@index']);
        Route::get('/categories', ['uses' => 'BookCategoryController@index']);
        Route::get('/{id}', ['uses' => 'BookController@show','middleware' => 'api.auth']);

        Route::post('/{book}/like', ['uses' => 'BookController@booklike','middleware' => 'api.auth']);
        Route::post('/{book}/share', ['uses' => 'BookController@bookshare','middleware' => 'api.auth']);
        Route::post('/{book}/gift', ['uses' => 'BookController@bookgift','middleware' => 'api.auth']);
        Route::post('/{book}/comment', ['uses' => 'BookController@bookcomment','middleware' => 'api.auth']);
        Route::post('/{book}/save', ['uses' => 'BookController@booksave','middleware' => 'api.auth']);
    });

    Route::get('advertising-banner', ['uses' => 'AdvertisingBannerController@list']);

    Route::get('/subscribe', ['uses' => 'SubscribesController@list']);

    Route::get('instructors', ['uses' => 'UserController@instructors']);
    Route::get('organizations', ['uses' => 'UserController@organizations']);

    Route::post('newsletter', ['uses' => 'UserController@makeNewsletter', 'middleware' => 'format']);
    Route::post('contact', ['uses' => 'ContactController@store', 'middleware' => 'format']);

    Route::group(['prefix' => 'regions'], function () {
        Route::get('/countries/', ['uses' => 'RegionsController@countries']);
        Route::get('/provinces/{id?}', ['uses' => 'RegionsController@provinces']);
        Route::get('/cities/{id?}', ['uses' => 'RegionsController@cities']);
        Route::get('/districts/{id?}', ['uses' => 'RegionsController@districts']);

    });
    Route::get('timezones', ['uses' => 'TimeZonesController@index']);

    /******  Bundles ******/
    Route::group(['prefix' => 'bundles'], function () {
        Route::get('/', ['uses' => 'BundleController@index']);
        Route::get('/{id}/webinars', ['uses' => 'BundleWebinarController@index']);
        Route::post('/{id}/free', ['uses' => 'BundleWebinarController@free']);
        Route::get('/{id}', ['uses' => 'BundleController@show']);
    });

    /******  Products ******/
    Route::group(['prefix' => 'products'], function () {
        //Route::post('/', ['uses' => 'ProductController@index']);
        Route::post('/', ['uses' => 'ProductController@index']);
        Route::get('/{id}', ['uses' => 'ProductController@show']);

        Route::post('/{product}/like', ['uses' => 'ProductController@productlike','middleware' => 'api.auth']);
        Route::post('/{product}/share', ['uses' => 'ProductController@productshare','middleware' => 'api.auth']);
        Route::post('/{product}/gift', ['uses' => 'ProductController@productgift','middleware' => 'api.auth']);
        Route::post('/{product}/comment', ['uses' => 'ProductController@productcomment','middleware' => 'api.auth']);
        Route::post('/{product}/save', ['uses' => 'ProductController@productsave','middleware' => 'api.auth']);

        // Route::post('/{product}/like', ['uses' => 'ProductController@productlike']);
        // Route::post('/{product}/share', ['uses' => 'ProductController@productshare']);
        // Route::post('/{product}/gift', ['uses' => 'ProductController@productgift']);
        // Route::post('/{product}/comment', ['uses' => 'ProductController@productcomment']);
        // Route::post('/{product}/save', ['uses' => 'ProductController@productsave']);
    });
    Route::get('/product_categories', ['uses' => 'ProductCategoryController@index']);
    Route::get('/sort', ['uses' => 'ProductController@getSortData']);
    //New API

    //get Countries Mobile Code
    Route::group(['prefix' => 'regions'], function () {
        Route::get('/countries/code', function () {
            return apiResponse2(
                1,
                'retrieved',
                trans('api.public.retrieved'),
                getCountriesMobileCode()
            );
        });
    });
    Route::get('/currency/list', function () {
        $multiCurrency = new MultiCurrency();
        $currencies = $multiCurrency->getCurrencies();
        return apiResponse2(
            1,
            'retrieved',
            trans('api.public.retrieved'),
            $currencies
        );
    });
    Route::post('/notification/new', function () {
        $title = request("title");
        $body = request("message");
        $token = request("token");

        $fcmMessage = \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $token);
        $fcmMessage = $fcmMessage->withNotification([
            'title' => $title,
            'body' => $body
        ]);
        $messaging = app('firebase.messaging');
        try {
            $response = $messaging->send($fcmMessage);
            return apiResponse2(1, "retrived", "", $response);
        } catch (Exception $exception) {
            return apiResponse2(1, "retrived", $exception->getMessage(), $exception->getTrace());
        }
    });

    //End New API
});









