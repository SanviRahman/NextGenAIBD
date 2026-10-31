<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

class HomepageHeroMedia
{
    public static function webRoot(): string
    {
        if ($configured = config('homepage_hero.public_root')) {
            return rtrim($configured, '/\\');
        }
        return WebsiteMedia::webRoot();
    }

    public static function upload(UploadedFile $file): string
    {
        $relativeDirectory = 'media/homepage/hero';
        $directory = self::webRoot() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativeDirectory);
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('Hero media directory is not writable: ' . $directory);
        }
        if (!is_writable($directory)) {
            throw new RuntimeException('Hero media directory is not writable: ' . $directory);
        }
        $name = (string) Str::uuid() . '.' . strtolower($file->extension());
        $file->move($directory, $name);
        return $relativeDirectory . '/' . $name;
    }

    // Intentionally no automatic deletion: files may be referenced by old revisions.
    // This prevents replacing a slide from destroying media used by another record.
}
