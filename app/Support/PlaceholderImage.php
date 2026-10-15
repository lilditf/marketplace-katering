<?php

namespace App\Support;

/**
 * Generator foto placeholder menggunakan ekstensi GD (bawaan PHP).
 *
 * Foto menu pada data seed dibuat sendiri secara programatik — bukan aset
 * pihak ketiga — sehingga repository tetap memenuhi ketentuan
 * "semua karya asli" dan tidak bergantung pada lisensi gambar eksternal.
 */
class PlaceholderImage
{
    /**
     * Buat file JPG 640x400: latar warna solid sesuai kategori, "piring"
     * dekoratif di tengah, dan judul menu di bagian bawah.
     *
     * @param  string  $absolutePath  Path lengkap file tujuan.
     * @param  string  $title         Judul menu yang digambar.
     * @param  string  $hexBg         Warna latar format #RRGGBB.
     */
    public static function make(string $absolutePath, string $title, string $hexBg): void
    {
        if (is_file($absolutePath)) {
            return; // idempoten: tidak menimpa foto yang sudah ada.
        }

        $w = 640;
        $h = 400;

        $img = imagecreatetruecolor($w, $h);
        [$r, $g, $b] = sscanf($hexBg, '#%02x%02x%02x');

        $bg = imagecolorallocate($img, $r, $g, $b);
        imagefilledrectangle($img, 0, 0, $w, $h, $bg);

        // Piring putih + lingkaran dalam semi-transparan.
        $white = imagecolorallocate($img, 255, 255, 255);
        imagefilledellipse($img, intdiv($w, 2), intdiv($h, 2) - 30, 230, 230, $white);
        $inner = imagecolorallocatealpha($img, $r, $g, $b, 60);
        imagefilledellipse($img, intdiv($w, 2), intdiv($h, 2) - 30, 165, 165, $inner);

        $dark = imagecolorallocate($img, 45, 32, 26);

        // Cari font TTF yang tersedia (Windows → Linux fallback).
        $font = null;
        foreach ([
            'C:/Windows/Fonts/arialbd.ttf',
            'C:/Windows/Fonts/arial.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
        ] as $candidate) {
            if (is_file($candidate)) {
                $font = $candidate;
                break;
            }
        }

        if ($font !== null) {
            // Perkecil ukuran huruf bila judul terlalu lebar.
            $size = 30;
            $box = imagettfbbox($size, 0, $font, $title);
            $tw = abs($box[2] - $box[0]);
            while ($tw > $w - 40 && $size > 14) {
                $size -= 2;
                $box = imagettfbbox($size, 0, $font, $title);
                $tw = abs($box[2] - $box[0]);
            }
            imagettftext($img, $size, 0, intdiv($w - $tw, 2), $h - 50, $dark, $font, $title);
        } else {
            // Fallback font bitmap bawaan GD (selalu tersedia).
            $tw = strlen($title) * imagefontwidth(5);
            imagestring($img, 5, max(5, intdiv($w - $tw, 2)), $h - 70, $title, $dark);
        }

        // Watermark kecil di pojok kiri atas.
        $markColor = imagecolorallocatealpha($img, 255, 255, 255, 60);
        imagestring($img, 2, 12, 12, 'Marketplace Katering - foto placeholder', $markColor);

        imagejpeg($img, $absolutePath, 82);
        imagedestroy($img);
    }
}
