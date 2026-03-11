<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    public function index()
    {
        // $this->authorize('admin_app_versions');

        $versions = AppVersion::get();

        return view('admin.app_version.index', [
            'pageTitle' => 'App Version Management',
            'versions'  => $versions,
        ]);
    }

    public function store(Request $request)
    {
        // $this->authorize('admin_app_versions_create');

        $this->validate($request, [
            'app_version'    => 'required|string|max:20',
            'update_message' => 'nullable|string|max:500',
        ]);

        AppVersion::create([
            'app_version'    => trim($request->app_version),
            'force_update'   => $request->boolean('force_update') ? 1 : 0,
            'update_message' => $request->update_message,
            'status'         => $request->boolean('status', true) ? 1 : 0,
        ]);

        return redirect(getAdminPanelUrl() . '/app_version')
            ->with('success', 'Version added successfully.');
    }

    public function edit($id)
    {
        // $this->authorize('admin_app_versions_edit');

        $versions    = AppVersion::get();
        $editVersion = AppVersion::findOrFail($id);

        return view('admin.app_version.index', [
            'pageTitle'   => 'App Version Management',
            'versions'    => $versions,
            'editVersion' => $editVersion,
        ]);
    }

    public function update(Request $request, $id)
    {
        // $this->authorize('admin_app_versions_edit');

        $this->validate($request, [
            'app_version'    => 'required|string|max:20',
            'update_message' => 'nullable|string|max:500',
        ]);

        $version = AppVersion::findOrFail($id);

        $version->update([
            'app_version'    => trim($request->app_version),
            'force_update'   => $request->boolean('force_update') ? 1 : 0,
            'update_message' => $request->update_message,
            'status'         => $request->boolean('status') ? 1 : 0,
        ]);

        return redirect(getAdminPanelUrl() . '/app_version')
            ->with('success', 'Version updated successfully.');
    }

    public function delete($id)
    {
        // $this->authorize('admin_app_versions_delete');

        AppVersion::findOrFail($id)->delete();

        return redirect(getAdminPanelUrl() . '/app_version')
            ->with('success', 'Version deleted.');
    }
}