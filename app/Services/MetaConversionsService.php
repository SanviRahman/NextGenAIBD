<?php

namespace App\Services;

use App\Models\MetaPixelIntegration;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class MetaConversionsService
{
    /**
     * Queue consented browser events for delivery after the HTTP response.
     * Individual events are not persisted in the application's database.
     */
    public function queueBrowserEvents(array $events, Request $request): void
    {
        if ($request->cookie('meta_marketing_consent') !== 'granted' || $events === []) {
            return;
        }

        $integrations = MetaPixelIntegration::query()->active()->get();
        if ($integrations->isEmpty()) {
            return;
        }

        $this->queueDelivery($integrations, $events, $this->baseUserData($request));
    }

    public function queueLead(Request $request, array $lead, string $eventId): void
    {
        $this->queueIdentityConversion(
            $request,
            'Lead',
            $lead,
            $eventId,
            route('home_Index'),
            ['content_name' => 'Free Consultation', 'content_category' => 'Lead']
        );
    }

    public function queueRegistration(Request $request, array $user, string $eventId): void
    {
        $this->queueIdentityConversion(
            $request,
            'CompleteRegistration',
            $user,
            $eventId,
            route('home_Index'),
            ['content_name' => 'Website Registration', 'status' => 'completed']
        );
    }

    public function queueApplication(Request $request, array $applicant, string $eventId): void
    {
        $this->queueIdentityConversion(
            $request,
            'SubmitApplication',
            $applicant,
            $eventId,
            route('frontend_Apply_Index'),
            ['content_name' => 'Course Application', 'content_category' => 'Application']
        );
    }

    private function queueIdentityConversion(
        Request $request,
        string $eventName,
        array $identity,
        string $eventId,
        string $sourceUrl,
        array $customData
    ): void {
        if ($request->cookie('meta_marketing_consent') !== 'granted') {
            return;
        }

        $integrations = MetaPixelIntegration::query()->active()->get();
        if ($integrations->isEmpty()) {
            return;
        }

        $userData = $this->baseUserData($request);
        $email = strtolower(trim((string) ($identity['email'] ?? '')));
        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $userData['em'] = [hash('sha256', $email)];
        }

        $phone = $this->normalizePhone((string) ($identity['phone'] ?? $identity['number'] ?? ''));
        if ($phone !== '') {
            $userData['ph'] = [hash('sha256', $phone)];
        }

        $this->queueDelivery($integrations, [[
            'event_name' => $eventName,
            'event_time' => now()->timestamp,
            'event_id' => $eventId,
            'event_source_url' => $sourceUrl,
            'action_source' => 'website',
            'custom_data' => $customData,
        ]], $userData);
    }

    /**
     * Send an explicit ADMIN diagnostic event, not a fabricated PageView.
     * A stable, pseudonymous ID for the authenticated admin is a real first-
     * party identifier; it prevents Meta's insufficient-user-data rejection
     * without inventing emails, public IP addresses or browser cookies.
     */
    public function sendTestEvent(MetaPixelIntegration $integration, Request $request): array
    {
        if (!$integration->is_active) {
            return ['success' => false, 'message' => 'Activate this Pixel integration before sending a test event.'];
        }

        if (blank($integration->access_token) || blank($integration->test_event_code)) {
            return ['success' => false, 'message' => 'Add a Conversions API access token and Test Event Code first.'];
        }

        $admin = $request->user('admin');
        if (!$admin || !$admin->getAuthIdentifier()) {
            return ['success' => false, 'message' => 'Please sign in as an administrator before testing CAPI.'];
        }

        $userData = $this->baseUserData($request);
        $userData['external_id'] = [hash_hmac(
            'sha256',
            'nextgenaibd-admin:' . (string) $admin->getAuthIdentifier(),
            (string) config('app.key')
        )];

        // Only share an actual admin email when this browser already opted in
        // to marketing tracking. Hash it just as we do for confirmed leads.
        if ($request->cookie('meta_marketing_consent') === 'granted') {
            $email = strtolower(trim((string) ($admin->email ?? '')));
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $userData['em'] = [hash('sha256', $email)];
            }
        }

        $payload = [
            'data' => [[
                'event_name' => 'NextGenAIBD_CAPI_Test',
                'event_time' => now()->timestamp,
                'event_id' => (string) Str::uuid(),
                // The admin clicked the test button on this website.
                'event_source_url' => $request->url(),
                'action_source' => 'website',
                'user_data' => $userData,
            ]],
            'test_event_code' => trim((string) $integration->test_event_code),
            'partner_agent' => 'NextGenAIBD-Laravel-CAPI',
        ];

        try {
            $response = Http::asJson()
                ->withToken((string) $integration->access_token)
                ->timeout((int) config('services.meta.timeout', 8))
                ->post($this->endpoint($integration), $payload);

            if ($response->successful() && (int) $response->json('events_received', 0) >= 1) {
                return [
                    'success' => true,
                    'message' => 'Meta accepted the server test event (NextGenAIBD_CAPI_Test). Check Events Manager > Test Events.',
                ];
            }

            $this->logMetaError($integration, $response, 'Meta CAPI test event rejected.');

            return ['success' => false, 'message' => $this->safeErrorMessage($response)];
        } catch (Throwable $exception) {
            Log::warning('Meta CAPI test event request failed.', [
                'pixel_id' => $integration->pixel_id,
                'exception' => class_basename($exception),
            ]);

            return [
                'success' => false,
                'message' => 'Meta API connection failed. Check the server SSL/network settings and storage/logs/laravel.log.',
            ];
        }
    }

    private function queueDelivery($integrations, array $events, array $userData): void
    {
        // Website events need the real browser User-Agent and at least one
        // usable matching identifier. Never repeatedly send UA-only localhost
        // events that Meta will reject as insufficient customer information.
        if (empty($userData['client_user_agent']) || !array_intersect(
            ['em', 'ph', 'external_id', 'fbp', 'fbc', 'client_ip_address'],
            array_keys($userData)
        )) {
            return;
        }

        $normalizedEvents = array_values(array_filter(array_map(function (array $event) use ($userData) {
            $eventName = (string) ($event['event_name'] ?? '');
            $eventId = (string) ($event['event_id'] ?? '');
            $sourceUrl = (string) ($event['event_source_url'] ?? '');

            if (!preg_match('/^[A-Za-z][A-Za-z0-9_]{0,49}$/D', $eventName)
                || !Str::isUuid($eventId)
                || !filter_var($sourceUrl, FILTER_VALIDATE_URL)) {
                return null;
            }

            $serverEvent = [
                'event_name' => $eventName,
                'event_time' => (int) ($event['event_time'] ?? now()->timestamp),
                'event_id' => $eventId,
                'event_source_url' => $sourceUrl,
                'action_source' => 'website',
                'user_data' => $userData,
            ];

            $customData = $this->safeCustomData($event['custom_data'] ?? []);
            if ($customData !== []) {
                $serverEvent['custom_data'] = $customData;
            }

            return $serverEvent;
        }, $events)));

        // A repeated event inside a browser batch must never become multiple
        // independent server sends. No cache/database entries are required.
        $uniqueEvents = [];
        foreach ($normalizedEvents as $event) {
            $key = $event['event_name'] . ':' . $event['event_id'];
            $uniqueEvents[$key] ??= $event;
        }
        $normalizedEvents = array_values($uniqueEvents);

        if ($normalizedEvents === []) {
            return;
        }

        app()->terminating(function () use ($integrations, $normalizedEvents): void {
            $this->deliver($integrations, $normalizedEvents);
        });
    }

    private function deliver($integrations, array $events): void
    {
        $integrations = $integrations->filter(function (MetaPixelIntegration $integration) {
            return $integration->is_active && filled($integration->access_token);
        })->values();

        if ($integrations->isEmpty()) {
            return;
        }

        try {
            $responses = Http::pool(function (Pool $pool) use ($integrations, $events) {
                $requests = [];
                foreach ($integrations as $integration) {
                    $key = 'pixel_' . $integration->id;
                    $requests[$key] = $pool
                        ->as($key)
                        ->asJson()
                        ->withToken((string) $integration->access_token)
                        ->withOptions([
                            'timeout' => (int) config('services.meta.timeout', 5),
                            'connect_timeout' => 2,
                        ])
                        ->post($this->endpoint($integration), [
                            'data' => $events,
                            // Test code is deliberately excluded from live events.
                            'partner_agent' => 'NextGenAIBD-Laravel-CAPI',
                        ]);
                }

                return $requests;
            });

            foreach ($integrations as $integration) {
                $response = $responses['pixel_' . $integration->id] ?? null;
                if ($response instanceof Response && !$response->successful()) {
                    $this->logMetaError($integration, $response, 'Meta CAPI event delivery returned an error.');
                } elseif (!$response instanceof Response) {
                    Log::warning('Meta CAPI event delivery failed.', [
                        'pixel_id' => $integration->pixel_id,
                        'exception' => is_object($response) ? class_basename($response) : 'No response',
                    ]);
                }
            }
        } catch (Throwable $exception) {
            Log::warning('Meta CAPI event delivery failed.', [
                'pixel_count' => $integrations->count(),
                'exception' => class_basename($exception),
            ]);
        }
    }

    private function logMetaError(MetaPixelIntegration $integration, Response $response, string $message): void
    {
        // Never log raw API responses or URLs: they can contain credentials/PII.
        Log::warning($message, [
            'pixel_id' => $integration->pixel_id,
            'http_status' => $response->status(),
            'meta_code' => $response->json('error.code'),
            'meta_subcode' => $response->json('error.error_subcode'),
            'fbtrace_id' => $response->json('error.fbtrace_id'),
        ]);
    }

    private function safeErrorMessage(Response $response): string
    {
        $status = $response->status();
        $subcode = (int) $response->json('error.error_subcode', 0);

        if ($subcode === 2804050) {
            return 'Meta rejected the event: insufficient customer matching information (2804050). Confirm admin sign-in and retry; see the Laravel log if it persists.';
        }
        if ($status === 401 || $status === 403 || (int) $response->json('error.code', 0) === 190) {
            return 'Meta authorization failed (HTTP ' . $status . '). Check the token and Dataset permissions.';
        }

        $code = (int) $response->json('error.code', 0);
        return 'Meta CAPI test rejected (HTTP ' . $status . ($code ? ', Meta code ' . $code : '')
            . ($subcode ? ', subcode ' . $subcode : '') . '). Check storage/logs/laravel.log.';
    }

    private function endpoint(MetaPixelIntegration $integration): string
    {
        $base = rtrim((string) config('services.meta.graph_url', 'https://graph.facebook.com'), '/');
        $version = trim((string) config('services.meta.graph_api_version', 'v26.0'), '/');

        // Credentials are sent in the Authorization header, never in the URL.
        return $base . '/' . $version . '/' . rawurlencode($integration->pixel_id) . '/events';
    }

    private function baseUserData(Request $request): array
    {
        $data = [];
        $userAgent = trim((string) $request->userAgent());
        if ($userAgent !== '') {
            $data['client_user_agent'] = Str::limit($userAgent, 1000, '');
        }

        // Do not invent 0.0.0.0 or send localhost/private network addresses.
        $ip = (string) $request->ip();
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            $data['client_ip_address'] = $ip;
        }

        $fbp = trim((string) $request->cookie('_fbp', ''));
        $fbc = trim((string) $request->cookie('_fbc', ''));
        if ($fbp !== '' && strlen($fbp) <= 255) {
            $data['fbp'] = $fbp;
        }
        if ($fbc !== '' && strlen($fbc) <= 255) {
            $data['fbc'] = $fbc;
        }

        // Created only after marketing opt-in; no database table is required.
        if ($request->cookie('meta_marketing_consent') === 'granted') {
            $visitor = trim((string) $request->cookie('meta_marketing_visitor', ''));
            if (Str::isUuid($visitor)) {
                $data['external_id'] = [hash_hmac('sha256', 'nextgenaibd-visitor:' . $visitor, (string) config('app.key'))];
            }
        }

        return $data;
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?: '';
        if ($digits === '') {
            return '';
        }

        // Normalize common Bangladesh numbers to international digits.
        if (str_starts_with($digits, '0')) {
            $digits = '88' . $digits;
        } elseif (strlen($digits) === 10 && !str_starts_with($digits, '880')) {
            $digits = '880' . $digits;
        }

        return $digits;
    }

    private function safeCustomData($customData): array
    {
        if (!is_array($customData)) {
            return [];
        }

        $allowed = [
            'content_name', 'content_category', 'content_ids', 'value', 'currency',
            'search_string', 'status', 'method', 'page_path', 'element_text', 'element_id',
            'element_type', 'link_url', 'scroll_percent', 'form_id', 'button_text',
        ];

        $safe = [];
        foreach ($allowed as $key) {
            if (!array_key_exists($key, $customData)) {
                continue;
            }

            $value = $customData[$key];
            if (is_array($value) && $key === 'content_ids') {
                $safe[$key] = array_slice(array_values(array_filter(array_map(
                    fn ($item) => is_scalar($item) ? Str::limit((string) $item, 100, '') : null,
                    $value
                ))), 0, 10);
            } elseif (is_numeric($value) && in_array($key, ['value', 'scroll_percent'], true)) {
                $safe[$key] = (float) $value;
            } elseif (is_scalar($value)) {
                $safe[$key] = Str::limit(trim((string) $value), 200, '');
            }
        }

        return $safe;
    }
}
