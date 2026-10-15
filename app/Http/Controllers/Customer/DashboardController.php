<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Dashboard kantor (customer): ringkasan order aktif, total transaksi,
 * dan order terbaru — halaman pertama setelah login.
 */
class DashboardController extends Controller
{
    /**
     * Dashboard kantor: order yang sedang berjalan, ringkasan belanja,
     * dan riwayat terbaru.
     */
    public function index(Request $request): View
    {
        $customer = $request->user()->customerProfile;

        $activeOrders = $customer->orders()
            ->whereIn('status', ['pending', 'dikonfirmasi', 'dikirim'])
            ->with(['merchantProfile', 'invoice'])
            ->latest()
            ->get();

        $totalOrders = $customer->orders()->count();
        $totalSpent = (float) $customer->orders()->where('status', 'selesai')->sum('subtotal');

        $recentOrders = $customer->orders()
            ->with(['merchantProfile', 'invoice'])
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact(
            'customer',
            'activeOrders',
            'totalOrders',
            'totalSpent',
            'recentOrders'
        ));
    }
}
