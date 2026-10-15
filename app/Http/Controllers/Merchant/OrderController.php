<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Fitur M4 — Daftar order masuk (portal merchant).
 * Daftar pesanan dengan filter status, detail order + invoice, dan
 * pembaruan status berurutan: pending → dikonfirmasi → dikirim → selesai
 * (atau dibatalkan).
 */
class OrderController extends Controller
{
    /**
     * Peta transisi status yang diizinkan.
     * Order "selesai"/"dibatalkan" bersifat final (tidak ada transisi keluar).
     */
    public const TRANSITIONS = [
        'pending' => ['dikonfirmasi', 'dibatalkan'],
        'dikonfirmasi' => ['dikirim', 'dibatalkan'],
        'dikirim' => ['selesai'],
        'selesai' => [],
        'dibatalkan' => [],
    ];

    /**
     * Daftar order masuk untuk merchant login, dengan filter status.
     */
    public function index(Request $request): View
    {
        $merchant = $request->user()->merchantProfile;

        $status = $request->query('status');
        if (! in_array($status, Order::STATUSES, true)) {
            $status = null; // parameter filter di luar whitelist diabaikan.
        }

        $orders = $merchant->orders()
            ->with(['customerProfile.user', 'invoice'])
            ->withCount('items')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Ringkasan jumlah per status untuk tab filter.
        $countByStatus = $merchant->orders()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('merchant.orders.index', compact('merchant', 'orders', 'status', 'countByStatus'));
    }

    public function show(Request $request, Order $order): View
    {
        $merchant = $request->user()->merchantProfile;

        // Otorisasi: hanya order yang ditujukan ke merchant ini.
        abort_if($order->merchant_profile_id !== $merchant->id, 403);

        $order->load(['customerProfile.user', 'items.menu', 'invoice']);
        $allowedNext = self::TRANSITIONS[$order->status] ?? [];

        return view('merchant.orders.show', compact('merchant', 'order', 'allowedNext'));
    }

    /**
     * Ubah status order dengan aturan transisi yang eksplisit,
     * misalnya order "selesai" tidak bisa dikembalikan ke "dikirim".
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $merchant = $request->user()->merchantProfile;
        abort_if($order->merchant_profile_id !== $merchant->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', Order::STATUSES)],
        ], [
            'status.required' => 'Status baru wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        $allowedTransitions = self::TRANSITIONS;

        if (! in_array($validated['status'], $allowedTransitions[$order->status] ?? [], true)) {
            return back()->with(
                'error',
                "Perubahan status dari \"{$order->statusLabel()}\" ke \"".Order::STATUS_LABELS[$validated['status']].'" tidak diizinkan.'
            );
        }

        $order->update(['status' => $validated['status']]);

        return back()->with('success', "Status order diperbarui menjadi \"{$order->statusLabel()}\".");
    }
}
