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

        $orders = Order::with(['merchantProfile', 'customerProfile'])
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'status' => $status,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['merchantProfile.user', 'customerProfile.user', 'items.menu', 'invoice']);

        return view('admin.orders.show', compact('order'));
    }
}
