<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use App\Models\Setting;
use App\Models\Translation\SettingTranslation;
use App\Models\WebinarReport;
use App\Models\Reel;
use App\Models\ReelReport;  
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function reasons(Request $request)
    {
        $this->authorize('admin_report_reasons');

        $value = [];

        $settings = Setting::where('name', 'report_reasons')->first();

        $locale = $request->get('locale', getDefaultLocale());
        storeContentLocale($locale, $settings->getTable(), $settings->id);

        if (!empty($settings) and !empty($settings->value)) {
            $value = json_decode($settings->value, true);
        }


        $data = [
            'pageTitle' => trans('admin/pages/setting.report_reasons'),
            'value' => $value,
        ];


        return view('admin.reports.reasons', $data);
    }

    public function storeReasons(Request $request)
    {
        $this->authorize('admin_report_reasons');

        $name = 'report_reasons';

        $values = $request->get('value', null);

        if (!empty($values)) {
            $locale = $request->get('locale', getDefaultLocale());

            $values = array_filter($values, function ($val) {
                if (is_array($val)) {
                    return array_filter($val);
                } else {
                    return !empty($val);
                }
            });

            $values = json_encode($values);
            $values = str_replace('record', rand(1, 600), $values);

            $settings = Setting::updateOrCreate(
                ['name' => $name],
                [
                    'updated_at' => time(),
                ]
            );

            SettingTranslation::updateOrCreate(
                [
                    'setting_id' => $settings->id,
                    'locale' => mb_strtolower($locale)
                ],
                [
                    'value' => $values,
                ]
            );

            cache()->forget('settings.' . $name);
        }

        removeContentLocale();

        return back();
    }

    public function webinarsReports()
    {
        $this->authorize('admin_webinar_reports');

        $reports = WebinarReport::with(['user' => function ($query) {
            $query->select('id', 'full_name');
        }, 'webinar' => function ($query) {
            $query->select('id', 'slug');
        }])->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/comments.classes_reports'),
            'reports' => $reports
        ];

        return view('admin.webinars.reports', $data);
    }

    public function delete($id)
    {
        $this->authorize('admin_webinar_reports_delete');

        $report = WebinarReport::findOrFail($id);

        $report->delete();

        return redirect()->back();
    }

    public function reelsReports()
    {
        // $this->authorize('admin_reel_reports');

        // Group reports by reel_id, eager-load reel + reporter users
        $reports = ReelReport::with([
                'user:id,full_name',
                'reel:id,title,user_id',
                'reel.user:id,full_name',
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('reel_id');   // collection keyed by reel_id

        $data = [
            'pageTitle' => "Portals Reports",
            'reports'   => $reports,
        ];

        return view('admin.reels.reports', $data);
    }

    public function reelReportShow($reelId)
    {
        // $this->authorize('admin_reel_reports');

        $reel = Reel::with('user:id,full_name')->findOrFail($reelId);

        $reports = ReelReport::with('user:id,full_name')
            ->where('reel_id', $reelId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => "Portals Reports",
            'reel'      => $reel,
            'reports'   => $reports,
        ];

        return view('admin.reels.report_detail', $data);
    }

    public function acceptReelReport($reelId)
    {
        // $this->authorize('admin_reel_reports_delete');

        $reel = Reel::findOrFail($reelId);

        // Delete all reports for this reel first, then delete the reel
        ReelReport::where('reel_id', $reelId)->delete();
        $reel->delete();

        return redirect()
            ->route('admin.reports.reels')
            ->with('success', 'Reel deleted and all reports cleared.');
    }

    public function declineReelReport($reelId)
    {
        // $this->authorize('admin_reel_reports_delete');

        // Keep the reel, just dismiss all its reports
        ReelReport::where('reel_id', $reelId)->delete();

        return redirect()->back()
            ->with('success', 'Reports dismissed.');
    }

    public function deleteOneReelReport($id)
    {
        // $this->authorize('admin_reel_reports_delete');

        ReelReport::findOrFail($id)->delete();

        return redirect()->back();
    }
}
