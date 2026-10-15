<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * Invoice hanya bisa dilihat oleh kantor pemesannya.
     * View invoice dipakai bersama dengan portal merchant.
     */
    public function show(Request $request, Invoice $invoice): View
    {
        $customer = $request->user()->customerProfile;

        abort_if($invoice->order->customer_profile_id !== $customer->id, 403);

        $invoice->load(['order.items.menu', 'order.merchantProfile.user', 'order.customerProfile.user']);

        return view('invoices.show', ['invoice' => $invoice, 'viewer' => 'customer']);
    }
}
