<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftReel;
use Illuminate\Http\Request;

class GiftReelController extends Controller
{
    public function index()
    {
        //$this->authorize('admin_giftreel_list');

        $giftReels = GiftReel::orderBy('created_at', 'desc')->paginate(10);

        $data = [
            'pageTitle' => 'Gift Reels',
            'giftReels' => $giftReels
        ];

        return view('admin.giftreel.lists', $data);
    }

    public function create()
    {
        //$this->authorize('admin_giftreel_create');

        $data = [
            'pageTitle' => 'Create New Gift Reel',
        ];

        return view('admin.giftreel.new', $data);
    }

    public function store(Request $request)
    {
        //$this->authorize('admin_giftreel_create');

        $this->validate($request, [
            'title' => 'required|string|max:556',
            'thumbnail' => 'required|string|max:556',
        ]);

        GiftReel::create([
            'title' => $request->input('title'),
            'thumbnail' => $request->input('thumbnail'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        return redirect(getAdminPanelUrl().'/giftreel')->with('success', 'Gift reel created successfully.');
    }

    public function edit($id)
    {
        //$this->authorize('admin_giftreel_edit');

        $giftReel = GiftReel::findOrFail($id);

        $data = [
            'pageTitle' => 'Edit Gift Reel',
            'giftReel' => $giftReel
        ];

        return view('admin.giftreel.new', $data);
    }

    public function update(Request $request, $id)
    {
        //$this->authorize('admin_giftreel_edit');

        $this->validate($request, [
            'title' => 'required|string|max:556',
            'thumbnail' => 'required|string|max:556',
        ]);

        $giftReel = GiftReel::findOrFail($id);

        $giftReel->update([
            'title' => $request->input('title'),
            'thumbnail' => $request->input('thumbnail'),
            'updated_at' => time(),
        ]);

        return redirect(getAdminPanelUrl().'/giftreel')->with('success', 'Gift reel updated successfully.');
    }

    public function delete($id)
    {
        //$this->authorize('admin_giftreel_delete');

        $giftReel = GiftReel::findOrFail($id);
        $giftReel->delete();

        return redirect(getAdminPanelUrl().'/giftreel')->with('success', 'Gift reel deleted successfully.');
    }
}