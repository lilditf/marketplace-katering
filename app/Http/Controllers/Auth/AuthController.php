<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CuisineType;
use App\Models\CustomerProfile;
use App\Models\MerchantProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Autentikasi ditulis manual (tanpa scaffolding/Breeze) sesuai ketentuan tes:
 * registrasi dengan pilihan role, login dengan throttle anti brute-force,
 * regenerasi session untuk mencegah session fixation, dan logout via POST.
 */
class AuthController extends Controller
{
    public function showRegister(Request $request): View
    {
        $role = $request->query('role') === 'merchant' ? 'merchant' : 'customer';

        return view('auth.register', [
            'role' => $role,
            // Daftar jenis makanan dikelola admin (tabel cuisine_types).
            'cuisineTypes' => CuisineType::activeNames(),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:merchant,customer'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['required', 'string', 'min:8', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'city' => ['required', 'string', 'max:100'],
            'company_name' => ['required_if:role,merchant', 'nullable', 'string', 'max:150'],
            'cuisine_type' => ['required_if:role,merchant', 'nullable', Rule::in(CuisineType::activeNames())],
            'office_name' => ['required_if:role,customer', 'nullable', 'string', 'max:150'],
        ], [
            'role.required' => 'Jenis pendaftaran wajib dipilih.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar. Silakan login.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.min' => 'Nomor telepon minimal 8 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'city.required' => 'Kota wajib diisi.',
            'company_name.required_if' => 'Nama perusahaan katering wajib diisi.',
            'cuisine_type.required_if' => 'Jenis makanan wajib dipilih.',
            'cuisine_type.in' => 'Jenis makanan tidak dikenali.',
            'office_name.required_if' => 'Nama kantor/instansi wajib diisi.',
        ]);

        // User + profilnya dibuat dalam satu transaction agar tidak ada
        // user "yatim" tanpa profil bila salah satu insert gagal.
        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            if ($user->isMerchant()) {
                MerchantProfile::create([
                    'user_id' => $user->id,
                    'company_name' => $validated['company_name'],
                    'city' => $validated['city'],
                    'cuisine_type' => $validated['cuisine_type'],
                ]);
            } else {
                CustomerProfile::create([
                    'user_id' => $user->id,
                    'office_name' => $validated['office_name'],
                    'city' => $validated['city'],
                    'pic_name' => $validated['name'],
                ]);
            }

            return $user;
        });

        Auth::login($user);
        // Cegah session fixation: ID session diganti setelah login.
        $request->session()->regenerate();

        return redirect()
            ->route($user->isMerchant() ? 'merchant.dashboard' : 'customer.dashboard')
            ->with('success', 'Pendaftaran berhasil. Selamat datang di Marketplace Katering!');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Throttle: maksimal 5 percobaan gagal per email+IP per menit.
        $throttleKey = Str::transliterate(Str::lower($credentials['email']).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login yang gagal. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect per role; admin dibuat via seeder (tidak bisa self-register).
        $home = match (true) {
            $user->isMerchant() => 'merchant.dashboard',
            $user->isAdmin() => 'admin.dashboard',
            default => 'customer.dashboard',
        };

        return redirect()
            ->intended(route($home))
            ->with('success', 'Login berhasil. Selamat bekerja!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        // Invalidate + regenerate token CSRF agar session lama tidak bisa dipakai ulang.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah keluar.');
    }
}
