<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * The application's physical web root is public_html on the live host,
 * but public/ on standard Laravel installations. DB paths stay relative.
 */
final class WebsiteMedia
{
    public const PLACEHOLDER = 'frontend/assets/images/sda-media-placeholder.svg';

    public static function webRoot(): string
    {
        if (is_file(base_path('index.php')) && (is_dir(base_path('media')) || is_dir(base_path('frontend')))) {
            return base_path();
        }
        return public_path();
    }

    public static function exists(?string $relativePath): bool
    {
        if (!is_string($relativePath)) {
            return false;
        }
        $path = ltrim(str_replace('\\', '/', trim($relativePath)), '/');
        if ($path === '' || str_contains($path, '..') || str_contains($path, '//') ||
            !preg_match('~^(?:media|frontend|backend)/[A-Za-z0-9 _./()\-]+$~D', $path)) {
            return false;
        }
        return is_file(self::webRoot() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path));
    }

    public static function url(?string $path, string $fallback = self::PLACEHOLDER): string
    {
        return asset(self::exists($path) ? ltrim((string) $path, '/') : $fallback);
    }

    /** Store only vetted images/videos, with randomly generated file names. */
    public static function upload(UploadedFile $file, string $subdirectory): string
    {
        if (!preg_match('~^media/homepage/(?:consultation|ratings)$~D', $subdirectory)) {
            throw new RuntimeException('Invalid media destination.');
        }
        $root = self::webRoot();
        $directory = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $subdirectory);
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('Cannot create image directory.');
        }
        if (!is_writable($directory)) {
            throw new RuntimeException('Image upload directory is not writable.');
        }
        $filename = (string) Str::uuid() . '.' . strtolower($file->extension());
        $file->move($directory, $filename);
        return $subdirectory . '/' . $filename;
    }
}
