<?php

namespace App\Console\Commands;

use App\Models\Menu;
use App\Support\PlaceholderImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Command artisan `app:regenerate-menu-images` — menggambar ulang ilustrasi
 * placeholder menu demo (digenerate sendiri via GD, sesuai aturan tes yang
 * melarang aset pihak ketiga) tanpa mengubah data database.
 */
class RegenerateMenuImages extends Command
{
    protected $signature = 'app:regenerate-menu-images';

    protected $description = 'Regenerate ilustrasi menu demo tanpa mengubah data database';

    public function handle(): int
    {
        $colors = [
            'Makanan Berat' => '#D9744F',
            'Makanan Ringan' => '#E3B23C',
            'Minuman' => '#4F97D9',
            'Paket' => '#5FA97A',
        ];

        $menus = Menu::all();

        foreach ($menus as $menu) {
            PlaceholderImage::make(
                Storage::disk('public')->path($menu->photo_path),
                $menu->name,
                $colors[$menu->category],
                overwrite: true
            );
        }

        $this->info("{$menus->count()} ilustrasi menu berhasil dibuat ulang.");

        return self::SUCCESS;
    }
}
