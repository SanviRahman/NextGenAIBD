<?php

namespace App\Support;

final class TestimonialVideoUrl
{
    // Accept only a specific YouTube/Vimeo VIDEO (no raw HTML or arbitrary iframe URLs).
    public static function embed(?string $url): ?string
    {
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }
        $parts = parse_url($url);
        if (!$parts || strtolower($parts['scheme'] ?? '') !== 'https' || isset($parts['user']) || isset($parts['pass'])) {
            return null;
        }
        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';
        $videoId = null;
        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'], true)) {
            if ($path === '/watch') {
                parse_str($parts['query'] ?? '', $query);
                $videoId = is_string($query['v'] ?? null) ? $query['v'] : null;
            } elseif (preg_match('~^/(?:embed|shorts|live)/([A-Za-z0-9_-]{11})/?$~D', $path, $match)) {
                $videoId = $match[1];
            }
        } elseif (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            if (preg_match('~^/([A-Za-z0-9_-]{11})/?$~D', $path, $match)) {
                $videoId = $match[1];
            }
        } elseif (in_array($host, ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'], true)) {
            if (preg_match('~^/(?:video/)?([0-9]{5,15})/?$~D', $path, $match)) {
                return 'https://player.vimeo.com/video/' . $match[1] . '?autoplay=1';
            }
        }
        return is_string($videoId) && preg_match('/^[A-Za-z0-9_-]{11}$/D', $videoId)
            ? 'https://www.youtube-nocookie.com/embed/' . $videoId . '?autoplay=1&rel=0'
            : null;
    }
}
