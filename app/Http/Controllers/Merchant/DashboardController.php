<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Dashboard katering (merchant): total order, omzet bulan ini,
 * menu terlaris, dan order terbaru yang perlu ditindaklanjuti.
 */
class DashboardController extends Controller
{
    /**
     * Dashboard merchant: ringkasan bisnis + order terbaru.
     * Semua angka dihitung dengan query agregat di sisi database
     * (bukan loop koleksi di PHP) agar hemat memori & cepat.
     */
    public function index(Request $request): View
    {
        $merchant = $request->user()->merchantProfile;

        $totalMenus = $merchant->menus()->count();
        $totalOrders = $merchant->orders()->count();
        $ordersPending = $merchant->orders()->where('status', 'pending')->count();

        // Omzet bulan berjalan: hanya order yang sudah selesai.
        $revenueMonth = (float) $merchant->orders()
            ->where('status', 'selesai')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('subtotal');

        // 5 menu terlaris berdasarkan total porsi (order tidak dibatalkan).
        $topMenus = OrderItem::query()
            ->select('menu_id', DB::raw('SUM(qty) as total_qty'))
            ->whereHas('order', function ($q) use ($merchant) {
                $q->where('merchant_profile_id', $merchant->id)
                    ->where('status', '!=', 'dibatalkan');
            })
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('menu')
            ->get();

        $recentOrders = $merchant->orders()
            ->with(['customerProfile.user', 'invoice'])
            ->latest()
            ->limit(5)
            ->get();

        return view('merchant.dashboard', compact(
            'merchant',
            'totalMenus',
            'totalOrders',
            'ordersPending',
            'revenueMonth',
            'topMenus',
            'recentOrders'
        ));
    }
}
