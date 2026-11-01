<?php

namespace App\Support;

/**
 * A stable, pseudonymous Meta event ID per *saved* business record.
 *
 * One confirmed action -> one ID, shared by its Browser Pixel and CAPI copy.
 * This does not create records, queues or additional database tables.
 */
final class MetaEventId
{
    private const SERVER_CONFIRMED = [
        'lead',
        'completeregistration',
        'submitapplication',
        'purchase',
    ];

    public static function isServerConfirmed(string $eventName): bool
    {
        return in_array(strtolower($eventName), self::SERVER_CONFIRMED, true);
    }

    public static function forRecord(string $eventName, int|string $recordId): string
    {
        if ($eventName === '' || (string) $recordId === '') {
            throw new \InvalidArgumentException('A saved record and event name are required.');
        }

        $key = (string) config('app.key');
        if ($key === '') {
            throw new \RuntimeException('APP_KEY is required for pseudonymous Meta event IDs.');
        }

        // HMAC conceals sequential record IDs, unlike a plain UUID-v5 derived
        // from a public namespace. The resulting UUID is deterministic per
        // action, but different for different actions or saved records.
        $hex = substr(hash_hmac('sha256', 'nextgen-meta-v1|' . $eventName . '|' . $recordId, $key), 0, 32);
        $hex[12] = '5';
        $hex[16] = dechex((hexdec($hex[16]) & 0x3) | 0x8);

        return substr($hex, 0, 8) . '-' . substr($hex, 8, 4) . '-'
            . substr($hex, 12, 4) . '-' . substr($hex, 16, 4)
            . '-' . substr($hex, 20, 12);
    }
}
