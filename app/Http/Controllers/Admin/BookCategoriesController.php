<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookCategory;
use App\Models\BookCategoryTranslation;
use Illuminate\Http\Request;

class BookCategoriesController extends Controller
{
    public function index()
    {
        //$this->authorize('admin_book_categories');
        removeContentLocale();

        $bookCategories = BookCategory::withCount('books')->get();

        $data = [
            'pageTitle' => 'Scrolls Categories',
            'bookCategories' => $bookCategories
        ];

        return view('admin.book.categories', $data);
    }

    public function store(Request $request)
    {
       // $this->authorize('admin_book_categories_create');

        $this->validate($request, [
            'title' => 'required|string',
        ]);

        $data = $request->all();

        $category = BookCategory::create([
            'slug' => BookCategory::makeSlug($data['title']),
        ]);

        BookCategoryTranslation::updateOrCreate([
            'book_category_id' => $category->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
        ]);

        return redirect(getAdminPanelUrl() . '/book/categories');
    }

    public function edit(Request $request, $category_id)
    {
        //$this->authorize('admin_book_categories_edit');

        $editCategory = BookCategory::findOrFail($category_id);

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $editCategory->getTable(), $editCategory->id);

        $data = [
            'pageTitle' => 'Scrolls Categories',
            'editCategory' => $editCategory
        ];

        return view('admin.book.categories', $data);
    }

    public function update(Request $request, $category_id)
    {
        //$this->authorize('admin_book_categories_edit');

        $this->validate($request, [
            'title' => 'required',
        ]);

        $category = BookCategory::findOrFail($category_id);

        $data = $request->all();

        BookCategoryTranslation::updateOrCreate([
            'book_category_id' => $category->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
        ]);

        return redirect(getAdminPanelUrl() . '/book/categories');
    }

    public function delete($category_id)
    {
        //$this->authorize('admin_book_categories_delete');

        $editCategory = BookCategory::findOrFail($category_id);

        $editCategory->delete();

        return redirect(getAdminPanelUrl() . '/book/categories');
    }
}