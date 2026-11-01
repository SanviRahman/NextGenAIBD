<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\MetaConversionsService;
use App\Support\MetaEventId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MetaPixelEventController extends Controller
{
    public function store(Request $request, MetaConversionsService $service): JsonResponse
    {
        if ($request->cookie('meta_marketing_consent') !== 'granted') {
            return response()->json(['accepted' => false, 'reason' => 'consent_required'], 202);
        }

        $data = $request->validate([
            'events' => ['required', 'array', 'min:1', 'max:20'],
            'events.*.event_name' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z][A-Za-z0-9_]{0,49}$/D'],
            'events.*.event_id' => ['required', 'uuid'],
            'events.*.event_time' => ['nullable', 'integer'],
            'events.*.event_source_url' => ['nullable', 'url', 'max:2048'],
            'events.*.custom_data' => ['nullable', 'array', 'max:15'],
        ]);

        $host = strtolower($request->getHost());
        $events = [];
        $seen = [];
        foreach ($data['events'] as $event) {
            // Browser-originated relay must not forge a *confirmed* conversion.
            // Registration/Lead/Application/Purchase are dispatched by Laravel
            // after a successful write, with one stable event ID shared by Pixel.
            if (MetaEventId::isServerConfirmed((string) $event['event_name'])) {
                continue;
            }

            $key = $event['event_name'] . ':' . $event['event_id'];
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $eventTime = (int) ($event['event_time'] ?? now()->timestamp);
            if ($eventTime < now()->subDays(7)->timestamp || $eventTime > now()->addMinutes(5)->timestamp) {
                continue;
            }

            $sourceUrl = (string) ($event['event_source_url'] ?? $request->headers->get('referer', config('app.url')));
            $sourceHost = strtolower((string) parse_url($sourceUrl, PHP_URL_HOST));
            if ($sourceHost === '' || $sourceHost !== $host) {
                $sourceUrl = rtrim((string) config('app.url'), '/') . '/';
            }

            $events[] = [
                'event_name' => $event['event_name'],
                'event_id' => (string) $event['event_id'],
                'event_time' => $eventTime,
                'event_source_url' => $sourceUrl,
                'custom_data' => $this->sanitizeCustomData($event['custom_data'] ?? []),
            ];
        }

        if ($events !== []) {
            $service->queueBrowserEvents($events, $request);
        }

        return response()->json([
            'accepted' => $events !== [],
            'event_count' => count($events),
        ], 202);
    }

    private function sanitizeCustomData($data): array
    {
        if (!is_array($data)) {
            return [];
        }

        $allowed = [
            'content_name', 'content_category', 'content_ids', 'value', 'currency',
            'search_string', 'status', 'method', 'page_path', 'element_text', 'element_id',
            'element_type', 'link_url', 'scroll_percent', 'form_id', 'button_text',
        ];
        $safe = [];

        foreach ($allowed as $key) {
            if (!array_key_exists($key, $data)) {
                continue;
            }

            $value = $data[$key];
            if ($key === 'content_ids' && is_array($value)) {
                $safe[$key] = array_slice(array_values(array_filter(array_map(
                    fn ($item) => is_scalar($item) ? Str::limit((string) $item, 100, '') : null,
                    $value
                ))), 0, 10);
            } elseif (in_array($key, ['value', 'scroll_percent'], true) && is_numeric($value)) {
                $safe[$key] = (float) $value;
            } elseif (is_scalar($value)) {
                $safe[$key] = Str::limit(trim((string) $value), 200, '');
            }
        }

        return $safe;
    }
}
