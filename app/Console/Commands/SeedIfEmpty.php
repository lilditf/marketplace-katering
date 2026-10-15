<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * Menjalankan db:seed HANYA jika tabel users masih kosong.
 * Dipakai di start command deploy (Railway): aman dipanggil berulang —
 * data demo tidak akan diduplikasi/ditimpa setelah seed pertama berhasil.
 */
class SeedIfEmpty extends Command
{
    protected $signature = 'app:seed-if-empty';

    protected $description = 'Seed database hanya jika belum ada user sama sekali';

    public function handle(): int
    {
        if (User::count() > 0) {
            $this->info('Database sudah terisi, seed dilewati.');

            return self::SUCCESS;
        }

        $this->info('Database kosong, menjalankan seeder demo...');
        Artisan::call('db:seed', ['--force' => true], $this->getOutput());

        return self::SUCCESS;
    }
}
