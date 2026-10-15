<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Fitur C2 — Pencarian Katering (portal customer).
 * Daftar katering dengan filter keyword/kota/jenis makanan + urutan &
 * pagination, serta halaman detail katering sebagai katalog menu.
 */
class CateringController extends Controller
{
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

        // Filter jenis makanan hanya diterima bila ada di whitelist.
        if (! in_array($cuisine, AuthController::CUISINE_TYPES, true)) {
            $cuisine = '';
        }

        $merchants = MerchantProfile::query()
            ->search($keyword ?: null, $city ?: null, $cuisine ?: null)
            ->with('user')
            ->withCount(['menus as available_menus_count' => function ($q) {
                $q->where('is_available', true);
            }])
            ->orderBy('company_name')
            ->paginate(9)
            ->withQueryString();

        $cities = MerchantProfile::where('is_active', true)
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('customer.caterings.index', [
            'merchants' => $merchants,
            'cities' => $cities,
            'cuisineTypes' => AuthController::CUISINE_TYPES,
            'keyword' => $keyword,
            'city' => $city,
            'cuisine' => $cuisine,
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
