<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Kelola katering (admin): daftar semua merchant, detail, dan aksi
 * aktif/nonaktif. Merchant nonaktif disembunyikan dari pencarian customer
 * tanpa menghapus data (riwayat order/invoice tetap utuh).
 */
class MerchantController extends Controller
{
    public function index(): View
    {
        $merchants = MerchantProfile::with('user')
            ->withCount(['menus', 'orders'])
            ->latest()
            ->paginate(15);

        return view('admin.merchants.index', compact('merchants'));
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
