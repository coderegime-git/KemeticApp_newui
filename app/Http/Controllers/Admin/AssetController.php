<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index()
    {
        // $this->authorize('admin_assets');
        
        $assets = Asset::get();

        $data = [
            'pageTitle' => 'Assets Management',
            'assets' => $assets
        ];

        return view('admin.asset.index', $data);
    }

    public function store(Request $request)
    {
        // $this->authorize('admin_assets_create');

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'file' => 'required|file|max:256000',
        ]);

        ini_set('upload_max_filesize', '250M');
        ini_set('post_max_size', '250M');

        $data = $request->all();

        

        // Handle file upload
        if ($request->hasFile('file')) {

            $video = $request->file('file');
            $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
            $videoPath = public_path('store/assets');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0777, true);
            }

            $video->move($videoPath, $filename);
            
            Asset::create([
                'user_id' => auth()->id(),
                'title' => $data['title'],
                'type' => $data['type'],
                'path' => $filename,
            ]);

            return redirect(getAdminPanelUrl() . '/asset')
                ->with('success', 'Asset uploaded successfully!');
        }

        return redirect()->back()
            ->with('error', 'File upload failed!');
    }

    public function update(Request $request, $id)
    {
        // $this->authorize('admin_assets_edit');

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'file' => 'nullable|file|max:10240',
        ]);

        $asset = Asset::findOrFail($id);
        $data = $request->all();

        // Handle file replacement
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($asset->path) {
                $this->deleteFile($asset->path);
            }
            
            $file = $request->file('file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $videoPath = public_path('store/assets');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0777, true);
            }
            $file->move($videoPath, $filename);
            $asset->path = $filename;
        }

        $asset->title = $data['title'];
        $asset->type = $data['type'];
        $asset->save();

        return redirect(getAdminPanelUrl() . '/asset')
            ->with('success', 'Asset updated successfully!');
    }

    public function delete($id)
    {
        // $this->authorize('admin_assets_delete');

        $asset = Asset::findOrFail($id);

        // Delete file from storage
        if ($asset->path) {
            $this->deleteFile($asset->path);
        }

        $asset->delete();

        return redirect(getAdminPanelUrl() . '/asset')
            ->with('success', 'Asset deleted successfully!');
    }
    
    private function deleteFile($filePath)
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }
        
        return false;
    }

    /**
     * Download file
     */
    public function download($id)
    {
        $asset = Asset::findOrFail($id);
        
        if (!$asset->path) {
            return redirect()->back()->with('error', 'File not found!');
        }

        $filePath = storage_path('app/public/' . $asset->path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        return response()->download($filePath, $asset->title . '.' . pathinfo($asset->path, PATHINFO_EXTENSION));
    }
}