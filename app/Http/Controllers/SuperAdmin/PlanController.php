<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::latest()->paginate(15);

        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::random(4);
        $data['available_modules'] = $request->input('available_modules', [
            'companies', 'contractors', 'patients', 'doctors', 'appointments',
            'medicines', 'prescriptions', 'medical_histories', 'reports',
        ]);

        SubscriptionPlan::create($data);

        return redirect()->route('superadmin.plans.index')->with('success', 'Plan created.');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('superadmin.plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $data = $this->validated($request, $plan->id);
        $plan->update($data);

        return redirect()->route('superadmin.plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        if ($plan->customers()->exists()) {
            return back()->with('error', 'Cannot delete a plan assigned to customers.');
        }

        $plan->delete();

        return redirect()->route('superadmin.plans.index')->with('success', 'Plan deleted.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'quarterly_price' => ['required', 'numeric', 'min:0'],
            'half_yearly_price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['required', 'numeric', 'min:0'],
            'trial_days' => ['nullable', 'integer', 'min:0'],
            'max_doctors' => ['required', 'integer', 'min:1'],
            'max_staff' => ['required', 'integer', 'min:1'],
            'max_patients' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
