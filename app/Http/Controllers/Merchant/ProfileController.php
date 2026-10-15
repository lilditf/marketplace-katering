<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\CuisineType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Fitur M2 — Profil perusahaan katering.
 * Nama perusahaan, kota, alamat, jenis makanan, deskripsi, kontak,
 * dan status aktif/nonaktif katalog.
 */
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('merchant.profile', [
            'merchant' => $request->user()->merchantProfile,
            'cuisineTypes' => CuisineType::activeNames(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $merchant = $request->user()->merchantProfile;

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'cuisine_type' => ['required', Rule::in(CuisineType::activeNames())],
            'description' => ['nullable', 'string', 'max:2000'],
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'min:8', 'max:20'],
        ], [
            'company_name.required' => 'Nama perusahaan wajib diisi.',
            'city.required' => 'Kota wajib diisi.',
            'cuisine_type.required' => 'Jenis makanan wajib dipilih.',
            'cuisine_type.in' => 'Jenis makanan tidak dikenali.',
            'name.required' => 'Nama penanggung jawab wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.min' => 'Nomor telepon minimal 8 karakter.',
        ]);

        // is_active berasal dari checkbox: tidak terkirim = false.
        $isActive = $request->boolean('is_active');

        DB::transaction(function () use ($merchant, $request, $validated, $isActive) {
            $merchant->update([
                'company_name' => $validated['company_name'],
                'city' => $validated['city'],
                'address' => $validated['address'] ?? null,
                'cuisine_type' => $validated['cuisine_type'],
                'description' => $validated['description'] ?? null,
                'is_active' => $isActive,
            ]);

            $request->user()->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]);
        });

        return redirect()
            ->route('merchant.profile.edit')
            ->with('success', 'Profil merchant berhasil diperbarui.');
    }
}
