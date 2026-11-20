<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Central\Customer;
use App\Models\Central\PasswordOtp;
use App\Models\Tenant\User;
use App\Services\Tenant\TenantConnectionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function __construct(protected TenantConnectionManager $connections) {}

    public function showLogin()
    {
        return view('tenant.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'tenant_code' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $key = 'tenant-login:'.$request->ip().':'.$data['tenant_code'];
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages(['email' => 'Too many login attempts.']);
        }

        $customer = Customer::with('database')
            ->where('tenant_code', $data['tenant_code'])
            ->first();

        if (! $customer || ! $customer->database || $customer->database->status !== 'ready') {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages(['tenant_code' => 'Invalid clinic code or account not ready.']);
        }

        if (! $customer->isAccessAllowed() || in_array($customer->account_status, ['suspended', 'inactive', 'failed'], true)) {
            throw ValidationException::withMessages(['tenant_code' => 'Clinic access is blocked. Contact support.']);
        }

        $this->connections->connect($customer);

        if (! Auth::guard('tenant')->attempt([
            'email' => $data['email'],
            'password' => $data['password'],
            'profile_status' => 'active',
        ], $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages(['email' => 'Invalid credentials.']);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();
        session([
            'tenant_customer_id' => $customer->id,
            'tenant_code' => $customer->tenant_code,
        ]);

        Auth::guard('tenant')->user()->update(['last_login_at' => now()]);
        $customer->update(['last_login_at' => now()]);

        return redirect()->route('tenant.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tenant.login');
    }

    public function showForgotPassword()
    {
        return view('tenant.auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'tenant_code' => ['required', 'string'],
            'email' => ['required', 'email'],
        ]);

        $customer = Customer::with('database')->where('tenant_code', $data['tenant_code'])->first();
        if (! $customer || ! $customer->database) {
            throw ValidationException::withMessages(['tenant_code' => 'Invalid clinic code.']);
        }

        $this->connections->connect($customer);
        $user = User::where('email', $data['email'])->first();
        if (! $user) {
            throw ValidationException::withMessages(['email' => 'Email not found for this clinic.']);
        }

        $otp = (string) random_int(100000, 999999);
        PasswordOtp::where('email', $data['email'])->where('tenant_code', $data['tenant_code'])->delete();
        PasswordOtp::create([
            'tenant_code' => $data['tenant_code'],
            'email' => $data['email'],
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        session([
            'otp_tenant_code' => $data['tenant_code'],
            'otp_email' => $data['email'],
        ]);

        try {
            Mail::to($data['email'])->send(new OtpMail($otp));
        } catch (\Throwable) {
            // Local fallback: flash OTP when mail not configured
            session()->flash('dev_otp', $otp);
        }

        return redirect()->route('tenant.otp.show')->with('success', 'OTP sent to your email (valid 10 minutes).');
    }

    public function showOtp()
    {
        if (! session('otp_email')) {
            return redirect()->route('tenant.password.request');
        }

        return view('tenant.auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $record = PasswordOtp::where('tenant_code', session('otp_tenant_code'))
            ->where('email', session('otp_email'))
            ->latest()
            ->first();

        if (! $record || ! $record->isValid() || $record->otp !== $data['otp']) {
            if ($record) {
                $record->increment('attempts');
            }
            throw ValidationException::withMessages(['otp' => 'Invalid or expired OTP.']);
        }

        $record->update(['verified_at' => now()]);
        session(['otp_verified' => true]);

        return redirect()->route('tenant.password.reset');
    }

    public function resendOtp(Request $request)
    {
        $request->merge([
            'tenant_code' => session('otp_tenant_code'),
            'email' => session('otp_email'),
        ]);

        return $this->sendOtp($request);
    }

    public function showResetPassword()
    {
        if (! session('otp_verified')) {
            return redirect()->route('tenant.password.request');
        }

        return view('tenant.auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (! session('otp_verified')) {
            return redirect()->route('tenant.password.request');
        }

        $data = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $customer = Customer::with('database')->where('tenant_code', session('otp_tenant_code'))->firstOrFail();
        $this->connections->connect($customer);

        User::where('email', session('otp_email'))->update([
            'password' => Hash::make($data['password']),
        ]);

        session()->forget(['otp_tenant_code', 'otp_email', 'otp_verified']);

        return redirect()->route('tenant.login')->with('success', 'Password updated. Please login.');
    }
}
