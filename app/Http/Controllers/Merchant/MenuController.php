<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $merchant = $request->user()->merchantProfile;

        // withCount: jumlah order per menu dalam satu query (hindari N+1),
        // dipakai untuk menandai menu yang tidak bisa dihapus.
        $menus = $merchant->menus()
            ->withCount('orderItems')
            ->latest()
            ->paginate(10);

        return view('merchant.menus.index', compact('merchant', 'menus'));
    }

    public function create(): View
    {
        return view('merchant.menus.create', ['categories' => Menu::CATEGORIES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $merchant = $request->user()->merchantProfile;
        $validated = $this->validateMenu($request);

        // Foto disimpan di storage/app/public/menus (disk "public"),
        // path relatifnya dicatat di kolom photo_path.
        $photoPath = $request->file('photo')->store('menus', 'public');
        unset($validated['photo']);

        $merchant->menus()->create($validated + [
            'photo_path' => $photoPath,
            'is_available' => $request->boolean('is_available', true),
        ]);

        return redirect()
            ->route('merchant.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        $this->authorize('update', $menu);

        return view('merchant.menus.edit', ['menu' => $menu, 'categories' => Menu::CATEGORIES]);
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $this->authorize('update', $menu);
        $validated = $this->validateMenu($request, requirePhoto: false);
        unset($validated['photo']);

        if ($request->hasFile('photo')) {
            // Simpan foto baru lebih dulu. Foto lama baru dihapus setelah
            // update database berhasil agar kegagalan upload tidak membuat
            // menu kehilangan foto yang masih valid.
            $oldPhotoPath = $menu->photo_path;
            $validated['photo_path'] = $request->file('photo')->store('menus', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available');
        $menu->update($validated);

        if (isset($oldPhotoPath) && $oldPhotoPath) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        return redirect()
            ->route('merchant.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $this->authorize('delete', $menu);

        // Menu yang sudah pernah dipesan TIDAK boleh dihapus agar riwayat
        // invoice tetap utuh (FK RESTRICT juga menolak di level database).
        if ($menu->orderItems()->exists()) {
            return redirect()
                ->route('merchant.menus.index')
                ->with('error', 'Menu tidak bisa dihapus karena sudah pernah dipesan. Gunakan toggle "Tersedia" untuk menyembunyikannya dari customer.');
        }

        if ($menu->photo_path) {
            Storage::disk('public')->delete($menu->photo_path);
        }
        $menu->delete();

        return redirect()
            ->route('merchant.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    public function toggleAvailable(Menu $menu): RedirectResponse
    {
        $this->authorize('update', $menu);

        $menu->update(['is_available' => ! $menu->is_available]);

        return back()->with(
            'success',
            $menu->is_available ? 'Menu ditandai tersedia.' : 'Menu ditandai tidak tersedia.'
        );
    }

    /**
     * Validasi server-side untuk form menu. Harga dibatasi minimal Rp1.000
     * dan maksimal Rp10.000.000 agar data tetap masuk akal.
     */
    private function validateMenu(Request $request, bool $requirePhoto = true): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:1000', 'max:10000000'],
            'category' => ['required', Rule::in(Menu::CATEGORIES)],
            'photo' => [$requirePhoto ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama menu wajib diisi.',
            'name.max' => 'Nama menu maksimal 150 karakter.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga minimal Rp1.000.',
            'price.max' => 'Harga maksimal Rp10.000.000.',
            'category.required' => 'Kategori wajib dipilih.',
            'category.in' => 'Kategori tidak dikenali.',
            'photo.required' => 'Foto menu wajib diunggah.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);
    }
}
