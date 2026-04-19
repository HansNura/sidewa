<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WargaAuthController extends Controller
{
    /**
     * Show the warga login form.
     */
    public function showLogin()
    {
        // Redirect if already logged in as warga
        if (Auth::guard('warga')->check()) {
            return redirect()->route('warga.dashboard');
        }

        return view('pages.frontend.layanan-mandiri.login');
    }

    /**
     * Authenticate a warga using NIK + PIN.
     */
    public function authenticate(Request $request)
    {
        // Validate input
        $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            'pin' => ['required', 'string', 'min:6', 'max:6'],
        ], [
            'nik.required'  => 'NIK wajib diisi.',
            'nik.size'      => 'NIK harus terdiri dari 16 digit.',
            'nik.regex'     => 'NIK hanya boleh berisi angka.',
            'pin.required'  => 'PIN wajib diisi.',
            'pin.min'       => 'PIN harus terdiri dari 6 digit.',
            'pin.max'       => 'PIN harus terdiri dari 6 digit.',
        ]);

        // Rate limiting: 5 attempts per minute per NIK+IP
        $throttleKey = 'warga-login:' . Str::lower($request->input('nik')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'nik' => ["Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik."],
            ]);
        }

        // Find warga by NIK
        $warga = Warga::where('nik', $request->input('nik'))->first();

        // Validate credentials
        if (! $warga || ! Hash::check($request->input('pin'), $warga->pin)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'nik' => ['NIK atau PIN yang Anda masukkan salah.'],
            ]);
        }

        // Check if account is active
        if (! $warga->is_active) {
            throw ValidationException::withMessages([
                'nik' => ['Akun Anda belum aktif atau telah dinonaktifkan. Silakan hubungi Operator Desa.'],
            ]);
        }

        // Clear rate limiter on success
        RateLimiter::clear($throttleKey);

        // Login the warga
        Auth::guard('warga')->login($warga);

        // Update last login time
        $warga->update(['last_login_at' => now()]);

        // Regenerate session
        $request->session()->regenerate();

        return redirect()->intended(route('warga.dashboard'));
    }

    /**
     * Logout the warga.
     */
    public function logout(Request $request)
    {
        Auth::guard('warga')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('layanan.mandiri.login')
            ->with('status', 'Anda telah berhasil keluar dari sistem.');
    }
}
