<?php

namespace App\Support;

/**
 * Generator ilustrasi menu menggunakan ekstensi GD bawaan PHP.
 *
 * Aset seed dibuat secara programatik agar repository tidak bergantung pada
 * foto atau template pihak ketiga.
 */
class PlaceholderImage
{
    /**
     * Buat ilustrasi menu JPG 640x400.
     */
    public static function make(string $absolutePath, string $title, string $hexBg, bool $overwrite = false): void
    {
        if (is_file($absolutePath) && ! $overwrite) {
            return;
        }

        $width = 640;
        $height = 400;
        $image = imagecreatetruecolor($width, $height);
        imageantialias($image, true);

        [$red, $green, $blue] = sscanf($hexBg, '#%02x%02x%02x');

        // Gradien hangat sebagai alas meja.
        for ($y = 0; $y < $height; $y++) {
            $factor = $y / $height;
            $line = imagecolorallocate(
                $image,
                min(255, (int) ($red + 24 * $factor)),
                min(255, (int) ($green + 18 * $factor)),
                min(255, (int) ($blue + 10 * $factor))
            );
            imageline($image, 0, $y, $width, $y, $line);
        }

        // Motif latar dan bayangan piring.
        $pattern = imagecolorallocatealpha($image, 255, 255, 255, 105);
        imagefilledellipse($image, 70, 55, 170, 170, $pattern);
        imagefilledellipse($image, 585, 360, 250, 250, $pattern);

        $shadow = imagecolorallocatealpha($image, 28, 38, 33, 82);
        imagefilledellipse($image, 326, 218, 330, 245, $shadow);

        $white = imagecolorallocate($image, 250, 248, 240);
        $rim = imagecolorallocate($image, 228, 225, 213);
        imagefilledellipse($image, 320, 200, 330, 245, $white);
        imagefilledellipse($image, 320, 200, 275, 195, $rim);
        imagefilledellipse($image, 320, 200, 258, 179, $white);

        // Komposisi makanan bervariasi tetapi deterministik berdasarkan nama.
        $seed = abs(crc32($title));
        $proteinPalette = [
            [151, 72, 43],
            [184, 95, 48],
            [116, 73, 52],
            [207, 122, 51],
        ];
        $proteinRgb = $proteinPalette[$seed % count($proteinPalette)];
        $protein = imagecolorallocate($image, ...$proteinRgb);
        $rice = imagecolorallocate($image, 246, 239, 216);
        $leaf = imagecolorallocate($image, 68, 130, 83);
        $leafLight = imagecolorallocate($image, 119, 166, 83);
        $sauce = imagecolorallocate($image, 111, 56, 35);

        imagefilledellipse($image, 270, 185, 118, 98, $rice);
        imagefilledellipse($image, 365, 205, 105, 72, $protein);
        imagefilledellipse($image, 366, 184, 82, 46, $protein);
        imagefilledellipse($image, 258, 230, 35, 28, $leaf);
        imagefilledellipse($image, 287, 238, 38, 25, $leafLight);
        imagefilledellipse($image, 307, 226, 28, 23, $leaf);
        imageline($image, 336, 179, 397, 212, $sauce);
        imageline($image, 340, 170, 398, 201, $sauce);

        imagejpeg($image, $absolutePath, 88);
        imagedestroy($image);
    }
}
