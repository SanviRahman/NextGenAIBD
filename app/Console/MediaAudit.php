<?php

namespace App\Console\Commands;

use App\Support\WebsiteMedia;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/** Read-only unless --repair; never alters DB or overwrites existing media. */
class MediaAudit extends Command
{
    protected $signature = 'media:audit {--repair : Restore images misplaced into public/media without overwriting} {--limit=30 : Maximum missing DB references to display}';
    protected $description = 'Audit database image references and recover misplaced Hostinger images';

    public function handle(): int
    {
        $root = WebsiteMedia::webRoot();
        $this->line('Web root: ' . $root);
        $this->line('Resolved public_path: ' . public_path());
        $source = base_path('public/media');
        $destination = $root . '/media';
        $recoverable = $restored = $failures = 0;
        if (is_dir($source) && realpath($source) !== realpath($destination)) {
            $sourceReal = realpath($source);
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS));
            foreach ($files as $file) {
                if (!$file->isFile() || $file->isLink()) continue;
                $from = realpath($file->getPathname());
                if (!$from || !str_starts_with($from, $sourceReal . DIRECTORY_SEPARATOR)) continue;
                $relative = substr($from, strlen($sourceReal) + 1);
                if (!in_array(strtolower(pathinfo($relative, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp','gif','mp4','webm'], true)) continue;
                $target = $destination . '/' . $relative;
                if (is_file($target)) continue;
                $recoverable++;
                if (!$this->option('repair')) {
                    $this->line('CAN RECOVER: media/' . $relative);
                    continue;
                }
                if (!is_dir(dirname($target)) && !@mkdir(dirname($target), 0755, true) && !is_dir(dirname($target))) {
                    $this->error('Cannot make directory for: media/' . $relative); $failures++; continue;
                }
                // Exclusive creation: never clobber an existing live image.
                $in = @fopen($from, 'rb');
                $out = @fopen($target, 'xb');
                if (!$in || !$out) {
                    if ($in) fclose($in);
                    if ($out) fclose($out);
                    $this->error('Cannot copy: media/' . $relative); $failures++; continue;
                }
                $copied = stream_copy_to_stream($in, $out);
                fclose($in); fclose($out);
                if ($copied !== $file->getSize()) {
                    @unlink($target); // Incomplete new copy only, never an existing file.
                    $failures++; $this->error('Incomplete copy: media/' . $relative); continue;
                }
                @chmod($target, 0644);
                $restored++;
                $this->info('RESTORED: media/' . $relative);
            }
        }
        $this->line("Misplaced: {$recoverable}; safely copied: {$restored}; copy failures: {$failures}");

        // Missing paths require restoring the actual asset or uploading it again.
        $tables = [
            'homepage_hero_slides' => ['main_image_path'=>'','background_shape_path'=>''],
            'homepage_highlights' => ['icon_path'=>''],
            'homepage_spotlight_videos' => ['poster_path'=>'','preview_video_path'=>''],
            'homepage_press_mentions' => ['logo_path'=>''],
            'homepage_consultation_settings' => ['image_path'=>''],
            'homepage_rating_platforms' => ['logo_path'=>''],
            'admins' => ['avatar'=>'media/admin/'],
            'logos' => ['frontlogo'=>'media/logo/','backlogo'=>'media/logo/','favicon'=>'media/logo/'],
            'services' => ['image'=>'media/service/'],
            'courses' => ['image'=>'media/course/'],
            'blogs' => ['image'=>'media/blog/'],
            'teams' => ['image'=>'media/team/'],
            'projects' => ['image'=>'media/project/'],
            'client_reviews' => ['image'=>'media/client_review/'],
            'abouts' => ['image'=>'media/about/'],
        ];
        $checked = $missing = 0;
        $limit = max(0, min(200, (int) $this->option('limit')));
        foreach ($tables as $table => $fields) {
            if (!Schema::hasTable($table)) continue;
            foreach ($fields as $field => $prefix) {
                if (!Schema::hasColumn($table, $field)) continue;
                foreach (DB::table($table)->whereNotNull($field)->where($field, '!=', '')->distinct()->pluck($field) as $value) {
                    $value = trim((string) $value);
                    if ($value === '' || str_contains($value, '://')) continue;
                    $path = $prefix === '' ? ltrim($value, '/') : $prefix . basename($value);
                    // Some older tables are reused for client logos/gallery/testimonials.
                    if ($table === 'abouts' && !WebsiteMedia::exists($path)) {
                        if (WebsiteMedia::exists('media/homepage/clients/' . basename($value)) ||
                            WebsiteMedia::exists('media/homepage/gallery/' . basename($value))) continue;
                    }
                    if ($table === 'client_reviews' && !WebsiteMedia::exists($path) &&
                        WebsiteMedia::exists('media/homepage/testimonials/' . basename($value))) continue;
                    $checked++;
                    if (!WebsiteMedia::exists($path)) {
                        $missing++;
                        if ($missing <= $limit) $this->warn("MISSING {$table}.{$field}: {$path}");
                    }
                }
            }
        }
        $this->line("Database image references checked: {$checked}; missing files: {$missing}");
        if ($missing > $limit) $this->warn('More missing paths omitted. Try --limit=100.');
        if ($missing > 0) $this->warn('Use a media backup or re-upload missing originals; this command does not modify DB records.');
        return $failures === 0 ? self::SUCCESS : self::FAILURE;
    }
}
