<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use Illuminate\View\View;

/**
 * Pantau kantor customer (admin): daftar + detail beserta ringkasan order.
 * Sengaja read-only; tidak ada data customer yang perlu diubah admin.
 */
class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = CustomerProfile::with('user')
            ->withCount('orders')
            ->latest()
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(CustomerProfile $customer): View
    {
        $customer->load(['user', 'orders.merchantProfile']);

        return view('admin.customers.show', compact('customer'));
    }
}
