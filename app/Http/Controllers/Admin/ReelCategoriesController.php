<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReelCategory;
use App\Models\ReelCategoryTranslation;
use Illuminate\Http\Request;

class ReelCategoriesController extends Controller
{
    public function index()
    {
        //$this->authorize('admin_reel_categories');
        removeContentLocale();

        $reelCategories = ReelCategory::withCount('reels')->get();

        $data = [
            'pageTitle' => 'Portals Categories',
            'reelCategories' => $reelCategories
        ];

        return view('admin.reel.categories', $data);
    }

    public function store(Request $request)
    {
        //$this->authorize('admin_reel_categories_create');

        $this->validate($request, [
            'title' => 'required|string',
        ]);

        $data = $request->all();

        $category = ReelCategory::create([
            'slug' => ReelCategory::makeSlug($data['title']),
        ]);

        ReelCategoryTranslation::updateOrCreate([
            'reel_category_id' => $category->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
        ]);

        return redirect(getAdminPanelUrl() . '/reel/categories');
    }

    public function edit(Request $request, $category_id)
    {
        //$this->authorize('admin_reel_categories_edit');

        $editCategory = ReelCategory::findOrFail($category_id);

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $editCategory->getTable(), $editCategory->id);

        $data = [
            'pageTitle' => 'Portals Categories',
            'editCategory' => $editCategory,
            'reelCategories' => ReelCategory::withCount('reels')->get()
        ];

        return view('admin.reel.categories', $data);
    }

    public function update(Request $request, $category_id)
    {
        //$this->authorize('admin_reel_categories_edit');

        $this->validate($request, [
            'title' => 'required',
        ]);

        $category = ReelCategory::findOrFail($category_id);

        $data = $request->all();

        ReelCategoryTranslation::updateOrCreate([
            'reel_category_id' => $category->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
        ]);

        return redirect(getAdminPanelUrl() . '/reel/categories');
    }

    public function delete($category_id)
    {
        //$this->authorize('admin_reel_categories_delete');

        $editCategory = ReelCategory::findOrFail($category_id);

        $editCategory->delete();

        return redirect(getAdminPanelUrl() . '/reel/categories');
    }
}