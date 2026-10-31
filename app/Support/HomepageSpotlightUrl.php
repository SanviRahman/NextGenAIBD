<?php

namespace App\Support;

class HomepageSpotlightUrl
{
    public static function youtubeId(?string $url): ?string
    {
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) return null;
        $parts = parse_url($url);
        if (($parts['scheme'] ?? '') !== 'https') return null;
        $host = strtolower($parts['host'] ?? '');
        $path = trim($parts['path'] ?? '', '/');
        $id = null;
        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'], true)) {
            if ($path === 'watch') {
                parse_str($parts['query'] ?? '', $q);
                $id = is_string($q['v'] ?? null) ? $q['v'] : null;
            } elseif (preg_match('~^(?:embed|shorts|live)/([a-zA-Z0-9_-]{11})$~', $path, $m)) $id = $m[1];
        } elseif ($host === 'youtu.be') {
            $id = $path;
        }
        return preg_match('/^[A-Za-z0-9_-]{11}$/D', (string) $id) ? $id : null;
    }

    public static function vimeoId(?string $url): ?string
    {
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) return null;
        $parts = parse_url($url);
        if (($parts['scheme'] ?? '') !== 'https') return null;
        if (!in_array(strtolower($parts['host'] ?? ''), ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'], true)) return null;
        $path = trim($parts['path'] ?? '', '/');
        return preg_match('~^(?:video/)?([0-9]{6,12})$~D', $path, $m) ? $m[1] : null;
    }

    public static function fullEmbedUrl(string $provider, string $url): ?string
    {
        if ($provider === 'youtube' && ($id = self::youtubeId($url))) {
            return 'https://www.youtube-nocookie.com/embed/' . $id . '?autoplay=1&rel=0&playsinline=1';
        }
        if ($provider === 'vimeo' && ($id = self::vimeoId($url))) {
            return 'https://player.vimeo.com/video/' . $id . '?autoplay=1&title=0&byline=0';
        }
        return null;
    }

    public static function mutedPreviewUrl(string $provider, string $url): ?string
    {
        if ($provider === 'youtube' && ($id = self::youtubeId($url))) {
            return 'https://www.youtube-nocookie.com/embed/' . $id . '?autoplay=1&mute=1&controls=0&loop=1&playlist=' . $id . '&playsinline=1&rel=0';
        }
        if ($provider === 'vimeo' && ($id = self::vimeoId($url))) {
            return 'https://player.vimeo.com/video/' . $id . '?background=1&autoplay=1&muted=1&loop=1';
        }
        return null;
    }
}
