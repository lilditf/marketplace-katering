<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\MerchantProfile;
use App\Models\Order;
use Illuminate\View\View;

/**
 * Dashboard admin: statistik global seluruh marketplace (bukan per-tenant
 * seperti dashboard merchant/customer).
 */
class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'merchantCount' => MerchantProfile::count(),
            'activeMerchantCount' => MerchantProfile::where('is_active', true)->count(),
            'customerCount' => CustomerProfile::count(),
            'orderCount' => Order::count(),
            // Omzet hanya dari order yang tidak dibatalkan.
            'omzet' => Order::where('status', '!=', 'dibatalkan')->sum('subtotal'),
            'latestOrders' => Order::with(['merchantProfile', 'customerProfile'])
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
