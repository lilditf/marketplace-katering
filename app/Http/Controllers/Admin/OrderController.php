<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Pantau semua order lintas merchant & customer (admin, read-only).
 * Admin sengaja TIDAK bisa mengubah status: alur status adalah wewenang
 * merchant sebagai pihak yang memproses pesanan.
 */
class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', '');
        $q = trim((string) $request->query('q', ''));
        $perPage = in_array((int) $request->query('per_page'), [10, 15, 25, 50], true)
            ? (int) $request->query('per_page') : 15;

        $orders = Order::with(['merchantProfile', 'customerProfile'])
            ->when($status !== '', fn ($query) => $query->where('status', $status))
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

        return view('admin.orders.index', [
            'orders' => $orders,
            'status' => $status,
            'q' => $q,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['merchantProfile.user', 'customerProfile.user', 'items.menu', 'invoice']);

        return view('admin.orders.show', compact('order'));
    }
}
