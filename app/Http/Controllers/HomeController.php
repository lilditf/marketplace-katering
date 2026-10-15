<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MerchantProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Halaman utama: guest melihat landing page publik,
     * user yang sudah login langsung diarahkan ke portalnya masing-masing.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return redirect()->route(
                $request->user()->isMerchant() ? 'merchant.dashboard' : 'customer.dashboard'
            );
        }

        // Data ringkas untuk landing page (agregat, bukan N+1).
        $merchantCount = MerchantProfile::where('is_active', true)->count();
        $menuCount = Menu::where('is_available', true)->count();
        $cities = MerchantProfile::where('is_active', true)
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('home', compact('merchantCount', 'menuCount', 'cities'));
    }
}
