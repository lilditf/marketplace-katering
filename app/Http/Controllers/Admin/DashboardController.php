<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\MerchantProfile;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Dashboard admin: statistik global seluruh marketplace (bukan per-tenant
 * seperti dashboard merchant/customer), chart order 7 hari terakhir,
 * distribusi status, dan tabel order terbaru berfitur data table standar
 * (pencarian, jumlah baris per halaman, pagination).
 */
class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $perPage = in_array((int) $request->query('per_page'), [10, 15, 25, 50], true)
            ? (int) $request->query('per_page') : 15;

        // Order per hari, 7 hari terakhir (untuk chart batang CSS murni).
        $perDay = Order::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $chartDays = collect(range(6, 0))->map(fn ($i) => [
            'label' => now()->subDays($i)->translatedFormat('D'),
            'total' => (int) ($perDay[now()->subDays($i)->toDateString()] ?? 0),
        ]);
        $chartMax = max(1, $chartDays->max('total'));

        // Distribusi order per status (untuk bar horizontal).
        $statusCounts = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Tabel order terbaru: data table standar (cari + per halaman + pagination).
        $latestOrders = Order::with(['merchantProfile', 'customerProfile'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($q2) use ($q) {
                    $q2->where('order_number', 'like', "%{$q}%")
                        ->orWhereHas('merchantProfile', fn ($q3) => $q3->where('company_name', 'like', "%{$q}%"))
                        ->orWhereHas('customerProfile', fn ($q3) => $q3->where('office_name', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.dashboard', [
            'merchantCount' => MerchantProfile::count(),
            'activeMerchantCount' => MerchantProfile::where('is_active', true)->count(),
            'customerCount' => CustomerProfile::count(),
            'orderCount' => Order::count(),
            // Omzet hanya dari order yang tidak dibatalkan.
            'omzet' => Order::where('status', '!=', 'dibatalkan')->sum('subtotal'),
            'chartDays' => $chartDays,
            'chartMax' => $chartMax,
            'statusCounts' => $statusCounts,
            'statuses' => Order::STATUSES,
            'latestOrders' => $latestOrders,
            'q' => $q,
        ]);
    }
}
