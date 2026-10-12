<?php

namespace App\Support\Credentials;

class CardAsset
{
    public static function dataUri(string $absolutePath): ?string
    {
        if (! is_file($absolutePath)) {
            return null;
        }

        $mime = mime_content_type($absolutePath) ?: 'image/png';
        $contents = file_get_contents($absolutePath);

        if ($contents === false) {
            return null;
        }

        return 'data:'.$mime.';base64,'.base64_encode($contents);
    }

    public static function publicImageDataUri(string $relativePublicPath): ?string
    {
        return static::dataUri(public_path(ltrim($relativePublicPath, '/')));
    }

    public static function ordinalDayMonthYear(\DateTimeInterface $date): string
    {
        $day = (int) $date->format('j');
        $suffix = match (true) {
            $day % 100 >= 11 && $day % 100 <= 13 => 'th',
            $day % 10 === 1 => 'st',
            $day % 10 === 2 => 'nd',
            $day % 10 === 3 => 'rd',
            default => 'th',
        };

        return $day.$suffix.' '.$date->format('M Y');
    }
}
