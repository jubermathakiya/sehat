<!DOCTYPE html>
<html><body style="font-family:Arial,sans-serif;color:#0B1F3A;">
<h2>Welcome to Sehat</h2>
<p>Your clinic portal is ready.</p>
<p><strong>Clinic:</strong> {{ $customer->clinic_name }}<br>
<strong>Tenant code:</strong> {{ $customer->tenant_code }}<br>
<strong>Email:</strong> {{ $customer->email }}<br>
<strong>Password:</strong> {{ $plainPassword }}</p>
<p>Login at: {{ url('/clinic/login') }}</p>
</body></html>