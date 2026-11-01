<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteInfo extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Return only valid social URLs that match their configured platform.
     * This prevents a LinkedIn URL accidentally stored in the Instagram field
     * from being rendered with the wrong social icon.
     */
    public function socialLinks(): array
    {
        $platforms = [
            'facebook' => [
                'label' => 'Facebook',
                'icon' => 'fab fa-facebook-f',
                'hosts' => ['facebook.com', 'fb.com'],
            ],
            'instagram' => [
                'label' => 'Instagram',
                'icon' => 'fab fa-instagram',
                'hosts' => ['instagram.com'],
            ],
            'twitter' => [
                'label' => 'X / Twitter',
                'icon' => 'fab fa-twitter',
                'hosts' => ['twitter.com', 'x.com'],
            ],
            'linkdin' => [
                'label' => 'LinkedIn',
                'icon' => 'fab fa-linkedin-in',
                'hosts' => ['linkedin.com'],
            ],
            'radit' => [
                'label' => 'Reddit',
                'icon' => 'fab fa-reddit-alien',
                'hosts' => ['reddit.com', 'redd.it'],
            ],
            'tiktok' => [
                'label' => 'TikTok',
                'icon' => 'fab fa-tiktok',
                'hosts' => ['tiktok.com'],
            ],
            'pintarest' => [
                'label' => 'Pinterest',
                'icon' => 'fab fa-pinterest-p',
                'hosts' => ['pinterest.com', 'pin.it'],
            ],
        ];

        $links = [];
        $seenUrls = [];

        foreach ($platforms as $field => $platform) {
            $url = trim((string) ($this->getAttribute($field) ?? ''));

            if ($url === '') {
                continue;
            }

            if (!preg_match('~^https?://~i', $url)) {
                $url = 'https://' . $url;
            }

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                continue;
            }

            $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
            $host = strtolower(rtrim((string) parse_url($url, PHP_URL_HOST), '.'));

            if (!in_array($scheme, ['http', 'https'], true) || $host === '') {
                continue;
            }

            $hostMatchesPlatform = false;

            foreach ($platform['hosts'] as $allowedHost) {
                if ($host === $allowedHost || str_ends_with($host, '.' . $allowedHost)) {
                    $hostMatchesPlatform = true;
                    break;
                }
            }

            if (!$hostMatchesPlatform) {
                continue;
            }

            $uniqueKey = strtolower(rtrim($url, '/'));

            if (isset($seenUrls[$uniqueKey])) {
                continue;
            }

            $seenUrls[$uniqueKey] = true;
            $links[] = [
                'label' => $platform['label'],
                'icon' => $platform['icon'],
                'url' => $url,
            ];
        }

        return $links;
    }

    /**
     * Build a WhatsApp click-to-chat URL from a local or international number.
     * Bangladeshi local mobile numbers are normalized to the 880 country code.
     */
    public function whatsappUrl(): ?string
    {
        $number = preg_replace('/\D+/', '', (string) $this->wattsup_number);

        if ($number === '') {
            return null;
        }

        if (str_starts_with($number, '00')) {
            $number = substr($number, 2);
        }

        if (str_starts_with($number, '0')) {
            $number = '880' . substr($number, 1);
        } elseif (strlen($number) === 10 && str_starts_with($number, '1')) {
            $number = '880' . $number;
        }

        return $number !== '' ? 'https://wa.me/' . $number : null;
    }
}
