<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Pantau kantor customer (admin): daftar + detail beserta ringkasan order.
 * Sengaja read-only; tidak ada data customer yang perlu diubah admin.
 */
class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $perPage = in_array((int) $request->query('per_page'), [10, 15, 25, 50], true)
            ? (int) $request->query('per_page') : 15;

        $customers = CustomerProfile::with('user')
            ->withCount('orders')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($q2) use ($q) {
                    $q2->where('office_name', 'like', "%{$q}%")
                        ->orWhere('pic_name', 'like', "%{$q}%")
                        ->orWhere('city', 'like', "%{$q}%")
                        ->orWhereHas('user', fn ($q3) => $q3->where('email', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'q'));
    }

    public function show(CustomerProfile $customer): View
    {
        $customer->load(['user', 'orders.merchantProfile']);

        return view('admin.customers.show', compact('customer'));
    }
}
