<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\Request;

class VersionCheckController extends Controller
{
    /**
     * Check if client app needs an update.
     *
     * GET /api/version/check?app_version=1.0.0
     *
     * Response:
     * {
     *   "status": true,
     *   "force_update": true,
     *   "latest_version": "2.0.0",
     *   "update_message": "Please update your app."
     * }
     */
    public function check(Request $request)
    {
        $this->validate($request, [
            'app_version' => 'required|string',
        ]);

        $latest = AppVersion::where('status', 1)->orderByDesc('id')->first();

        if (! $latest) {
            return response()->json([
                'status'       => false,
                'message'      => 'No version record found.',
            ], 404);
        }

        $clientVersion = trim($request->app_version);
        $serverVersion = trim($latest->app_version);

        // If versions are identical → no update needed
        $isSame      = version_compare($clientVersion, $serverVersion, '=');
        $forceUpdate = ! $isSame && (bool) $latest->force_update;
        $needsUpdate = ! $isSame;

        return response()->json([
            'status'         => true,
            'needs_update'   => $needsUpdate,
            'force_update'   => $forceUpdate,
            'latest_version' => $serverVersion,
            'update_message' => $needsUpdate
                                    ? ($latest->update_message ?? 'A new version is available. Please update.')
                                    : null,
        ]);
    }
}