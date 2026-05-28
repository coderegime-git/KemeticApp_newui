<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LivestreamSetting;
use Illuminate\Http\Request;

class LivestreamSettingController extends Controller
{
    public function index()
    {
        $settings = LivestreamSetting::orderBy('created_at', 'desc')->paginate(10);
        
        $data = [
            'pageTitle' => 'Livestream Settings',
            'settings' => $settings
        ];

        return view('admin.livestream_settings.index', $data);
    }

    public function create()
    {
        $data = [
            'pageTitle' => 'New Livestream Setting'
        ];

        return view('admin.livestream_settings.create', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'app_id' => 'required|string|max:255',
            'app_sign' => 'required|string|max:255',
        ]);

        LivestreamSetting::create([
            'app_id' => $request->input('app_id'),
            'app_sign' => $request->input('app_sign'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Livestream Setting successfully created.',
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl() . '/livestream-settings')->with(['toast' => $toastData]);
    }

    public function edit($id)
    {
        $setting = LivestreamSetting::findOrFail($id);

        $data = [
            'pageTitle' => 'Edit Livestream Setting',
            'setting' => $setting
        ];

        return view('admin.livestream_settings.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'app_id' => 'required|string|max:255',
            'app_sign' => 'required|string|max:255',
        ]);

        $setting = LivestreamSetting::findOrFail($id);
        $setting->update([
            'app_id' => $request->input('app_id'),
            'app_sign' => $request->input('app_sign'),
            'updated_at' => time(),
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Livestream Setting successfully updated.',
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl() . '/livestream-settings')->with(['toast' => $toastData]);
    }

    public function destroy($id)
    {
        $setting = LivestreamSetting::findOrFail($id);
        $setting->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Livestream Setting successfully deleted.',
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl() . '/livestream-settings')->with(['toast' => $toastData]);
    }
}
