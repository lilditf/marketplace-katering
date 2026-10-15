<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CuisineType;
use App\Models\MerchantProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Fitur C2 — Pencarian Katering (portal customer).
 * Daftar katering dengan filter keyword/kota/jenis makanan, urutan pilihan
 * user, dan pagination, serta halaman detail katering sebagai katalog menu.
 */
class CateringController extends Controller
{
    /**
     * Urutan hasil pencarian yang dikenal. Kunci di luar daftar ini
     * ditolak dan jatuh kembali ke urutan nama (parameter asing diabaikan).
     */
    private const SORTS = ['nama', 'kota', 'menu'];

    /**
     * Pencarian katering: keyword bebas + filter kota + filter jenis makanan,
     * dengan pagination. Query dibangun lewat scope MerchantProfile::search()
     * memakai whereHas/where terparameterisasi (aman dari SQL injection).
     */
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q', ''));
        $city = (string) $request->query('city', '');
        $cuisine = (string) $request->query('cuisine', '');
        $sort = (string) $request->query('sort', 'nama');

        // Filter jenis makanan hanya diterima bila ada di whitelist.
        if (! in_array($cuisine, CuisineType::activeNames(), true)) {
            $cuisine = '';
        }

        // Urutan hanya diterima bila ada di whitelist.
        if (! in_array($sort, self::SORTS, true)) {
            $sort = 'nama';
        }

        $merchants = MerchantProfile::query()
            ->search($keyword ?: null, $city ?: null, $cuisine ?: null)
            ->with('user')
            ->withCount(['menus as available_menus_count' => function ($q) {
                $q->where('is_available', true);
            }])
            ->when($sort === 'menu', fn ($q) => $q->orderByDesc('available_menus_count')->orderBy('company_name'))
            ->when($sort === 'kota', fn ($q) => $q->orderBy('city')->orderBy('company_name'))
            ->when($sort === 'nama', fn ($q) => $q->orderBy('company_name'))
            ->paginate(9)
            ->withQueryString();

        $cities = MerchantProfile::where('is_active', true)
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('customer.caterings.index', [
            'merchants' => $merchants,
            'cities' => $cities,
            'cuisineTypes' => CuisineType::activeNames(),
            'keyword' => $keyword,
            'city' => $city,
            'cuisine' => $cuisine,
            'sort' => $sort,
        ]);
    }

    /**
     * Detail katering: profil + daftar menu tersedia yang dikelompokkan
     * per kategori, siap dipesan.
     */
    public function show(MerchantProfile $catering): View
    {
        // Katering nonaktif tidak bisa diakses publik.
        abort_unless($catering->is_active, 404);

        $catering->load('user');

        $menus = $catering->menus()
            ->available()
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('customer.caterings.show', compact('catering', 'menus'));
    }
}
