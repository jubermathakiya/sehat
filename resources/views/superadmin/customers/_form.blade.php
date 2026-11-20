@php($customer = $customer ?? null)
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="clinic_name">Clinic name</label>
        <input type="text" name="clinic_name" id="clinic_name" class="form-control" value="{{ old('clinic_name', $customer?->clinic_name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="doctor_name">Doctor name</label>
        <input type="text" name="doctor_name" id="doctor_name" class="form-control" value="{{ old('doctor_name', $customer?->doctor_name) }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $customer?->email) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="mobile">Mobile</label>
        <input type="text" name="mobile" id="mobile" class="form-control" value="{{ old('mobile', $customer?->mobile) }}">
    </div>

    <div class="col-12">
        <label class="form-label" for="address">Address</label>
        <textarea name="address" id="address" class="form-control" rows="2">{{ old('address', $customer?->address) }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="city">City</label>
        <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $customer?->city) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label" for="state">State</label>
        <input type="text" name="state" id="state" class="form-control" value="{{ old('state', $customer?->state) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label" for="country">Country</label>
        <input type="text" name="country" id="country" class="form-control" value="{{ old('country', $customer?->country) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label" for="username">Admin username</label>
        <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $customer?->username) }}" required>
    </div>
    @unless(!empty($editing))
    <div class="col-md-6">
        <label class="form-label" for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">
    </div>
    @endunless
    @if(!empty($editing))
    <div class="col-md-6">
        <label class="form-label" for="account_status">Account status</label>
        <select name="account_status" id="account_status" class="form-select" required>
            @foreach(['active','inactive','suspended','expired'] as $st)
                <option value="{{ $st }}" @selected(old('account_status', $customer?->account_status)===$st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
    </div>
    @endif

    <div class="col-md-6">
        <label class="form-label" for="subscription_plan_id">Subscription plan</label>
        <select name="subscription_plan_id" id="subscription_plan_id" class="form-select" required>
            <option value="">Select plan</option>
            @foreach($plans ?? [] as $plan)
                <option value="{{ $plan->id }}" @selected(old('subscription_plan_id', $customer?->subscription_plan_id) == $plan->id)>{{ $plan->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="billing_cycle">Billing cycle</label>
        <select name="billing_cycle" id="billing_cycle" class="form-select" required @disabled(!empty($editing))>
            @foreach(['trial','monthly','quarterly','half_yearly','yearly'] as $cycle)
                <option value="{{ $cycle }}" @selected(old('billing_cycle', $customer?->billing_cycle ?? 'monthly') === $cycle)>{{ ucfirst(str_replace('_', ' ', $cycle)) }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="subscription_start_date">Subscription start</label>
        <input type="date" name="subscription_start_date" id="subscription_start_date" class="form-control" value="{{ old('subscription_start_date', optional($customer?->subscription_start_date)->format('Y-m-d') ?? now()->toDateString()) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label" for="grace_period_days">Grace period (days)</label>
        <input type="number" min="0" name="grace_period_days" id="grace_period_days" class="form-control" value="{{ old('grace_period_days', $customer?->grace_period_days ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label" for="discount">Discount</label>
        <input type="number" step="0.01" min="0" name="discount" id="discount" class="form-control" value="{{ old('discount', 0) }}">
    </div>
</div>
