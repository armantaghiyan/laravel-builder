<?php

namespace App\Core\Infrastructure\Services;

use RuntimeException;

class CaptchaImage {

    public function render(string $answer): string {
        if (!extension_loaded('gd')) {
            throw new RuntimeException('The GD extension is required to generate login captchas.');
        }

        $image = imagecreatetruecolor(240, 80);
        $background = imagecolorallocate($image, 247, 249, 252);
        imagefill($image, 0, 0, $background);
        imageantialias($image, true);

        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($image, random_int(175, 220), random_int(185, 225), random_int(205, 235));
            imagefilledellipse($image, random_int(0, 239), random_int(0, 79), 2, 2, $color);
        }

        foreach (str_split($answer) as $index => $character) {
            $glyph = imagecreatetruecolor(18, 24);
            $transparent = imagecolorallocatealpha($glyph, 255, 255, 255, 127);
            imagealphablending($glyph, false);
            imagefill($glyph, 0, 0, $transparent);
            imagesavealpha($glyph, true);
            $ink = imagecolorallocate($glyph, random_int(35, 70), random_int(45, 85), random_int(100, 145));
            imagechar($glyph, 5, 4, 4, $character, $ink);
            $glyph = imagescale($glyph, 36, 48, IMG_BICUBIC);
            $glyph = imagerotate($glyph, random_int(-16, 16), $transparent);
            imagesavealpha($glyph, true);
            imagecopy($image, $glyph, 10 + $index * 36, random_int(10, 18), 0, 0, imagesx($glyph), imagesy($glyph));
        }

        for ($i = 0; $i < 3; $i++) {
            $color = imagecolorallocatealpha($image, 99, 91, 180, 70);
            imagearc($image, random_int(65, 175), random_int(25, 55), 230, random_int(35, 65), 0, 180, $color);
        }

        ob_start();

        try {
            imagepng($image);

            return 'data:image/png;base64,' . base64_encode(ob_get_contents());
        } finally {
            ob_end_clean();
        }
    }
}
