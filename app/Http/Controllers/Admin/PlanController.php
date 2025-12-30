<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        //$this->authorize('admin_plan_list');

        $query = Plan::query();
        $plans = $this->filters($query, $request)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => 'Subscription Plans',
            'plans' => $plans,
        ];

        return view('admin.plan.lists', $data);
    }

    private function filters($query, $request)
    {
        $search = $request->get('search', null);
        $status = $request->get('status', null);
        $minPrice = $request->get('min_price', null);
        $maxPrice = $request->get('max_price', null);
        $from = $request->get('from', null);
        $to = $request->get('to', null);

        // Date filter
        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        // Search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                  ->orWhere('title', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        // Status filter
        if (!empty($status)) {
            $isActive = $status == 'active' ? 1 : 0;
            $query->where('is_membership', $isActive);
        }

        // Price filters
        if (!empty($minPrice)) {
            $query->where('price', '>=', $minPrice);
        }

        if (!empty($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    public function create()
    {
        //$this->authorize('admin_plan_create');

        $data = [
            'pageTitle' => 'Create New Plan',
        ];

        return view('admin.plan.create', $data);
    }

    public function store(Request $request)
    {
        //$this->authorize('admin_plan_create');

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:plans,code',
            'title' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_membership' => 'boolean'
        ]);

        $planData = [
            'code' => $validated['code'],
            'title' => $validated['title'],
            'price' => $validated['price'],
            'duration_days' => $validated['duration_days'],
            'is_membership' => $validated['is_membership'],
            'created_at' => time(), // Use now() instead of time()
            'updated_at' => time(),
        ];
        // dd($planData);
        Plan::create($planData);

        return redirect(getAdminPanelUrl().'/plan')
            ->with('success', 'Plan created successfully.');
    }

    public function edit($id)
    {
        //$this->authorize('admin_plan_edit');

        $plan = Plan::findOrFail($id);
        
        // Decode features if exists
        if ($plan->features) {
            $plan->features = json_decode($plan->features, true);
        }

        $data = [
            'pageTitle' => 'Edit Plan',
            'plan' => $plan,
        ];

        return view('admin.plan.create', $data);
    }

    public function update(Request $request, $id)
    {
        //$this->authorize('admin_plan_edit');

        $plan = Plan::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:plans,code,' . $id,
            'title' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_membership' => 'boolean'
        ]);
        
        //$validated['is_membership'] = $request->has('is_membership') ? 1 : 0;

        $planData = [
            'code' => $validated['code'],
            'title' => $validated['title'],
            'price' => $validated['price'],
            'duration_days' => $validated['duration_days'],
            'is_membership' => $validated['is_membership'],
            'updated_at' => time(),
        ];
        
        $plan->update($planData);

        return redirect(getAdminPanelUrl().'/plan')
            ->with('success', 'Plan updated successfully.');
    }

    public function delete($id)
    {
        //$this->authorize('admin_plan_delete');

        $plan = Plan::findOrFail($id);
        
        // Check if plan has active subscriptions before deleting
        // You might want to add this check if you have subscriptions table
        /*
        if ($plan->subscriptions()->count() > 0) {
            return redirect(getAdminPanelUrl().'/plan')
                ->with('error', 'Cannot delete plan with active subscriptions.');
        }
        */
        
        $plan->delete();

        return redirect(getAdminPanelUrl().'/plan')
            ->with('success', 'Plan deleted successfully.');
    }

    public function toggleStatus($id)
    {
        //$this->authorize('admin_plan_edit');

        $plan = Plan::findOrFail($id);
        $plan->update([
            'is_membership' => !$plan->is_membership,
            'updated_at' => time(),
        ]);

        $status = $plan->is_membership ? 'activated' : 'deactivated';

        return redirect(getAdminPanelUrl().'/plan')
            ->with('success', "Plan {$status} successfully.");
    }
}