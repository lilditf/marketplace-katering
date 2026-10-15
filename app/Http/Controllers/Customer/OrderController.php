<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Menu;
use App\Models\MerchantProfile;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Fitur C3 & C4 — Pembelian + penerbitan invoice otomatis.
 * Form order (menu, porsi, tanggal kirim) dan proses store: harga selalu
 * dihitung ulang dari database (anti price-tampering), order + item +
 * invoice dibuat atomik dalam satu DB::transaction.
 */
class OrderController extends Controller
{
    /**
     * Form pemesanan pada halaman katering tertentu.
     */
    public function create(MerchantProfile $catering): View
    {
        abort_unless($catering->is_active, 404);

        $customer = request()->user()->customerProfile;

        $menus = $catering->menus()
            ->available()
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('customer.orders.create', compact('catering', 'customer', 'menus'));
    }

    /**
     * Simpan pemesanan. Prinsip keamanan & integritas:
     * - Harga dan subtotal dihitung ULANG di server dari database,
     *   bukan dipercaya dari input form (anti price tampering).
     * - Semua menu dipastikan milik katering ini & masih tersedia.
     * - Order + item + invoice dibuat dalam SATU transaction.
     */
    public function store(Request $request, MerchantProfile $catering): RedirectResponse
    {
        abort_unless($catering->is_active, 404);
        $customer = $request->user()->customerProfile;

        $validated = $request->validate([
            'delivery_date' => ['required', 'date', 'after_or_equal:tomorrow', 'before:+6 months'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1', 'max:50'],
            'items.*.menu_id' => ['required', 'integer', 'exists:menus,id'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:10000'],
        ], [
            'delivery_date.required' => 'Tanggal pengiriman wajib dipilih.',
            'delivery_date.date' => 'Format tanggal tidak valid.',
            'delivery_date.after_or_equal' => 'Tanggal pengiriman minimal besok.',
            'delivery_date.before' => 'Tanggal pengiriman maksimal 6 bulan ke depan.',
            'items.required' => 'Pilih minimal satu menu.',
            'items.min' => 'Pilih minimal satu menu.',
            'items.max' => 'Maksimal 50 baris menu dalam satu pesanan.',
            'items.*.menu_id.required' => 'Menu wajib dipilih.',
            'items.*.menu_id.exists' => 'Menu tidak dikenali.',
            'items.*.qty.required' => 'Jumlah porsi wajib diisi.',
            'items.*.qty.integer' => 'Jumlah porsi harus bilangan bulat.',
            'items.*.qty.min' => 'Jumlah porsi minimal 1.',
            'items.*.qty.max' => 'Jumlah porsi maksimal 10.000.',
        ]);

        // Gabungkan baris duplikat (menu yang sama dipilih dua kali).
        $items = collect($validated['items'])
            ->groupBy('menu_id')
            ->map(fn ($group) => [
                'menu_id' => (int) $group->first()['menu_id'],
                'qty' => (int) $group->sum('qty'),
            ])
            ->values();

        // Batas per baris tetap harus diperiksa setelah menu duplikat
        // digabungkan. Tanpa pemeriksaan ini, banyak baris menu yang sama
        // dapat dipakai untuk melewati batas 10.000 porsi.
        if ($items->contains(fn ($item) => $item['qty'] > 10000)) {
            return back()
                ->withInput()
                ->withErrors(['items' => 'Jumlah maksimal untuk satu menu adalah 10.000 porsi.']);
        }

        // Semua menu harus milik katering INI dan masih tersedia —
        // mencegah order lintas merchant lewat manipulasi menu_id.
        $menus = Menu::whereKey($items->pluck('menu_id'))
            ->where('merchant_profile_id', $catering->id)
            ->where('is_available', true)
            ->get()
            ->keyBy('id');

        if ($menus->count() !== $items->count()) {
            return back()
                ->withInput()
                ->with('error', 'Beberapa menu tidak valid atau sudah tidak tersedia. Silakan periksa kembali pesanan Anda.');
        }

        // Hitung uang di server: subtotal per baris dari harga DB terkini.
        $lines = [];
        foreach ($items as $item) {
            $menu = $menus[$item['menu_id']];
            $lines[] = [
                'menu_id' => $menu->id,
                'qty' => $item['qty'],
                'price' => (float) $menu->price,
                'subtotal' => round((float) $menu->price * $item['qty'], 2),
            ];
        }
        $subtotal = round(array_sum(array_column($lines, 'subtotal')), 2);

        $order = DB::transaction(function () use ($catering, $customer, $validated, $lines, $subtotal) {
            // Nomor final diturunkan dari id (unik dijamin DB, anti race condition):
            // insert dengan nomor sementara acak, lalu update dalam transaction yang sama.
            $order = Order::create([
                'order_number' => 'TMP-'.Str::random(12),
                'merchant_profile_id' => $catering->id,
                'customer_profile_id' => $customer->id,
                'delivery_date' => $validated['delivery_date'],
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'status' => 'pending',
            ]);

            $order->update([
                'order_number' => sprintf('ORD-%s-%04d', $order->created_at->format('Ymd'), $order->id),
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'menu_id' => $line['menu_id'],
                    'qty' => $line['qty'],
                    'price_snapshot' => $line['price'],
                    'subtotal' => $line['subtotal'],
                ]);
            }

            // Invoice terbit otomatis 1:1 saat order dibuat,
            // dapat diakses kedua belah pihak.
            $invoice = $order->invoice()->create([
                'invoice_number' => 'TMP-'.Str::random(12),
                'issued_at' => now()->toDateString(),
                'total' => $subtotal,
                'payment_status' => 'belum_lunas',
            ]);

            $invoice->update([
                'invoice_number' => sprintf('INV-%s-%04d', now()->format('Ymd'), $order->id),
            ]);

            return $order;
        });

        return redirect()
            ->route('customer.orders.show', $order)
            ->with('success', "Order {$order->order_number} berhasil dibuat. Invoice otomatis diterbitkan.");
    }

    /**
     * Riwayat order milik kantor yang sedang login.
     */
    public function index(Request $request): View
    {
        $customer = $request->user()->customerProfile;

        $status = $request->query('status');
        if (! in_array($status, Order::STATUSES, true)) {
            $status = null;
        }

        $orders = $customer->orders()
            ->with(['merchantProfile', 'invoice'])
            ->withCount('items')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('customer.orders.index', compact('customer', 'orders', 'status'));
    }

    public function show(Request $request, Order $order): View
    {
        $customer = $request->user()->customerProfile;

        // Otorisasi: hanya order milik kantor ini.
        abort_if($order->customer_profile_id !== $customer->id, 403);

        $order->load(['merchantProfile.user', 'items.menu', 'invoice']);

        return view('customer.orders.show', compact('customer', 'order'));
    }
}
