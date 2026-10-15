<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CuisineType;
use App\Models\MerchantProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kelola daftar resmi jenis makanan (admin).
 * Daftar ini dipakai di form registrasi merchant, edit profil merchant,
 * filter pencarian customer, dan validasi di ketiganya. Jenis yang sudah
 * dipakai merchant tidak bisa dihapus (cukup dinonaktifkan) agar label di
 * profil lama tidak menjadi yatim.
 */
class CuisineController extends Controller
{
    public function index(): View
    {
        $cuisines = CuisineType::query()
            ->withCount(['merchants'])
            ->orderBy('name')
            ->get();

        return view('admin.cuisines.index', compact('cuisines'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:cuisine_types,name'],
        ], [
            'name.required' => 'Nama jenis makanan wajib diisi.',
            'name.unique' => 'Jenis makanan sudah ada.',
        ]);

        CuisineType::create($validated);

        return back()->with('success', "Jenis makanan \"{$validated['name']}\" ditambahkan.");
    }

    public function update(Request $request, CuisineType $cuisine): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:cuisine_types,name,'.$cuisine->id],
        ], [
            'name.required' => 'Nama jenis makanan wajib diisi.',
            'name.unique' => 'Jenis makanan sudah ada.',
        ]);

        $cuisine->update($validated);

        return back()->with('success', 'Jenis makanan diperbarui.');
    }

    public function toggle(CuisineType $cuisine): RedirectResponse
    {
        $cuisine->update(['is_active' => ! $cuisine->is_active]);

        return back()->with(
            'success',
            $cuisine->is_active
                ? "Jenis \"{$cuisine->name}\" diaktifkan."
                : "Jenis \"{$cuisine->name}\" disembunyikan dari pilihan baru."
        );
    }

    public function destroy(CuisineType $cuisine): RedirectResponse
    {
        // Guard: jenis yang masih dipakai profil merchant tidak boleh dihapus.
        $dipakai = MerchantProfile::where('cuisine_type', $cuisine->name)->exists();

        if ($dipakai) {
            return back()->with('error', "Jenis \"{$cuisine->name}\" masih dipakai katering. Nonaktifkan saja agar profil lama tidak rusak.");
        }

        $cuisine->delete();

        return back()->with('success', "Jenis makanan \"{$cuisine->name}\" dihapus.");
    }
}
