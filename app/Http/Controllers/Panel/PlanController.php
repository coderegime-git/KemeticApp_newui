<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\User;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive',
        ]);

        $query = Plan::query();
        
        // Search filter
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('code', 'LIKE', '%' . $request->get('search') . '%')
                  ->orWhere('title', 'LIKE', '%' . $request->get('search') . '%');
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $isActive = $request->get('status') == 'active' ? 1 : 0;
            $query->where('is_membership', $isActive);
        }
    
        // Price range filter (now safe)
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->get('min_price'));
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->get('max_price'));
        }
        
        // Order by latest
        $query->orderBy('created_at', 'desc');
        
        $plans = $query->paginate(10);
        
        $data = [
            'pageTitle' => 'Subscription Plans',
            'plans' => $plans,
        ];
        
        return view(getTemplate() . '.panel.plan.lists', $data);
    }
    
    public function create()
    {
        $data = [
            'pageTitle' => 'Create New Plan',
        ];
        
        return view(getTemplate() . '.panel.plan.create', $data);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:plans,code',
            'title' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_membership' => 'boolean'
        ]);
        
        $validated['is_membership'] = $request->has('is_membership') ? 1 : 0;

        $planData = [
            'code' => $validated['code'],
            'title' => $validated['title'],
            'price' => $validated['price'],
            'duration_days' => $validated['duration_days'],
            'is_membership' => $request->has('is_membership') ? 1 : 0,
            'created_at' => time(), // Use now() instead of time()
            'updated_at' => time(),
        ];

        //dd($planData);
        
        Plan::create($planData);
        
        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Plan created successfully.',
            'status' => 'success'
        ];
        
        return redirect('panel/plan')->with(['toast' => $toastData]);
    }
    
    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        
        $data = [
            'pageTitle' => 'Edit Plan',
            'plan' => $plan,
        ];
        
        return view(getTemplate() . '.panel.plan.create', $data);
    }
    
    public function update(Request $request, $id)
    {
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
            'is_membership' => $request->has('is_membership') ? 1 : 0,
            'updated_at' => time(),
        ];
        
        $plan->update($planData);
        
        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Plan updated successfully.',
            'status' => 'success'
        ];
        
        return redirect('panel/plan/')->with(['toast' => $toastData]);
    }
    
    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();
        
        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Plan deleted successfully.',
            'status' => 'success'
        ];
        
        return redirect('panel/plan/')->with(['toast' => $toastData]);
    }
    
    public function toggleStatus($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->update(['is_membership' => !$plan->is_membership]);
        
        $status = $plan->is_membership ? 'activated' : 'deactivated';
        
        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => "Plan {$status} successfully.",
            'status' => 'success'
        ];
        
        return redirect('panel/plan/')->with(['toast' => $toastData]);
    }
}