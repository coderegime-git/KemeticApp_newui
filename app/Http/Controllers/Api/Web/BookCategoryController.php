<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\BookCategory;

class BookCategoryController extends Controller
{
    public function index(){

        $categories=BookCategory::all()->map(function($category){
            return $category->details ;
        }) ;
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),$categories);
    }
}
