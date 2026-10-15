<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kelola katering (admin): daftar semua merchant, detail, dan aksi
 * aktif/nonaktif. Merchant nonaktif disembunyikan dari pencarian customer
 * tanpa menghapus data (riwayat order/invoice tetap utuh).
 */
class MerchantController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        // Jumlah baris per halaman dibatasi ke whitelist agar tidak disalahgunakan.
        $perPage = in_array((int) $request->query('per_page'), [10, 15, 25, 50], true)
            ? (int) $request->query('per_page') : 15;

        $merchants = MerchantProfile::with('user')
            ->withCount(['menus', 'orders'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($q2) use ($q) {
                    $q2->where('company_name', 'like', "%{$q}%")
                        ->orWhere('city', 'like', "%{$q}%")
                        ->orWhereHas('user', fn ($q3) => $q3->where('email', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.merchants.index', compact('merchants', 'q'));
    }

    public function show(MerchantProfile $merchant): View
    {
        $merchant->load(['user', 'menus']);
        $merchant->loadCount('orders');

        return view('admin.merchants.show', compact('merchant'));
    }

    public function toggleActive(MerchantProfile $merchant): RedirectResponse
    {
        $merchant->update(['is_active' => ! $merchant->is_active]);

        return back()->with(
            'success',
            $merchant->is_active
                ? "Katering {$merchant->company_name} diaktifkan kembali."
                : "Katering {$merchant->company_name} dinonaktifkan dan disembunyikan dari pencarian."
        );
    }
}
