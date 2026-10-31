<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

class HomepageSpotlightMedia
{
    public static function webRoot(): string
    {
        return WebsiteMedia::webRoot();
    }

    public static function upload(UploadedFile $file, string $folder): string
    {
        if (!in_array($folder, ['spotlight', 'press'], true)) {
            throw new RuntimeException('Invalid media destination.');
        }
        $relative = 'media/homepage/' . $folder;
        $absolute = self::webRoot() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
        if (!is_dir($absolute) && !mkdir($absolute, 0755, true) && !is_dir($absolute)) {
            throw new RuntimeException('Media folder cannot be created.');
        }
        if (!is_writable($absolute)) {
            throw new RuntimeException('Media folder is not writable.');
        }
        $fileName = (string) Str::uuid() . '.' . strtolower($file->extension());
        $file->move($absolute, $fileName);
        return $relative . '/' . $fileName;
    }

    // Keep replaced files for rollback; no destructive filesystem cleanup.
}
