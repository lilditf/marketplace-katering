<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * Detail invoice milik merchant (view invoice dipakai bersama portal
     * customer; variabel $viewer menentukan tombol/aksi yang tampil).
     */
    public function show(Request $request, Invoice $invoice): View
    {
        $merchant = $request->user()->merchantProfile;

        // Otorisasi: invoice hanya bisa dilihat merchant penerbitnya.
        abort_if($invoice->order->merchant_profile_id !== $merchant->id, 403);

        $invoice->load(['order.items.menu', 'order.customerProfile.user', 'order.merchantProfile.user']);

        return view('invoices.show', ['invoice' => $invoice, 'viewer' => 'merchant']);
    }

    /**
     * Tandai invoice lunas setelah pembayaran diterima (konfirmasi manual).
     */
    public function markPaid(Request $request, Invoice $invoice): RedirectResponse
    {
        $merchant = $request->user()->merchantProfile;
        abort_if($invoice->order->merchant_profile_id !== $merchant->id, 403);

        if ($invoice->isPaid()) {
            return back()->with('error', 'Invoice ini sudah berstatus lunas.');
        }

        $invoice->update([
            'payment_status' => 'lunas',
            'paid_at' => now(),
        ]);

        return back()->with('success', "Invoice {$invoice->invoice_number} ditandai LUNAS.");
    }
}
