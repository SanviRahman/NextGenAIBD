<?php

namespace Tests\Feature;

use App\Http\Controllers\Frontend\MetaPixelConsentController;
use App\Models\Admin;
use App\Models\MetaPixelIntegration;
use App\Services\MetaConversionsService;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MetaCapiTest extends TestCase
{
    private function integration(): MetaPixelIntegration
    {
        $integration = new MetaPixelIntegration();
        $integration->forceFill([
            'pixel_id' => '2100861233911035',
            'name' => 'NextGenAIBD Main Website',
            'access_token' => 'test_access_token_no_live_credentials',
            'test_event_code' => 'TEST77890',
            'is_active' => true,
        ]);

        return $integration;
    }

    private function adminRequest(bool $consented = true): Request
    {
        $admin = new Admin();
        $admin->id = 42;
        $admin->email = 'consented-admin@example.com';

        $request = Request::create(
            'http://127.0.0.1:8000/admin/dashboard/marketing/meta-pixel/integrations/1/test',
            'POST',
            [],
            $consented ? ['meta_marketing_consent' => 'granted'] : [],
            [],
            ['REMOTE_ADDR' => '127.0.0.1', 'HTTP_USER_AGENT' => 'Mozilla/5.0 Unit Test Browser']
        );
        $request->setUserResolver(fn ($guard = null) => $guard === 'admin' ? $admin : null);

        return $request;
    }

    #[Test]
    public function it_sends_a_diagnostic_with_matching_identifiers_and_a_bearer_token(): void
    {
        config(['app.key' => 'base64:' . base64_encode(str_repeat('x', 32))]);
        Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1], 200)]);

        $result = app(MetaConversionsService::class)->sendTestEvent($this->integration(), $this->adminRequest());

        $this->assertTrue($result['success']);
        Http::assertSent(function (HttpRequest $request) {
            $event = $request['data'][0];

            return $request->method() === 'POST'
                && str_ends_with($request->url(), '/2100861233911035/events')
                && !str_contains($request->url(), 'access_token')
                && $request->hasHeader('Authorization', 'Bearer test_access_token_no_live_credentials')
                && $request['test_event_code'] === 'TEST77890'
                && $event['event_name'] === 'NextGenAIBD_CAPI_Test'
                && $event['action_source'] === 'website'
                && $event['user_data']['client_user_agent'] === 'Mozilla/5.0 Unit Test Browser'
                && !isset($event['user_data']['client_ip_address'])
                && preg_match('/^[a-f0-9]{64}$/', $event['user_data']['external_id'][0]) === 1
                && $event['user_data']['em'][0] === hash('sha256', 'consented-admin@example.com');
        });
    }

    #[Test]
    public function it_reports_metas_insufficient_user_data_error_without_leaking_secrets(): void
    {
        config(['app.key' => 'base64:' . base64_encode(str_repeat('x', 32))]);
        Http::fake(['graph.facebook.com/*' => Http::response([
            'error' => ['code' => 100, 'error_subcode' => 2804050, 'message' => 'Insufficient customer information'],
        ], 400)]);

        $result = app(MetaConversionsService::class)->sendTestEvent($this->integration(), $this->adminRequest(false));

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('2804050', $result['message']);
        $this->assertStringNotContainsString('test_access_token', $result['message']);
    }

    #[Test]
    public function it_requires_an_authenticated_admin_for_testing(): void
    {
        config(['app.key' => 'base64:' . base64_encode(str_repeat('x', 32))]);
        Http::fake();
        $request = Request::create('http://127.0.0.1:8000/test', 'POST');
        $request->setUserResolver(fn ($guard = null) => null);

        $result = app(MetaConversionsService::class)->sendTestEvent($this->integration(), $request);
        $this->assertFalse($result['success']);
        Http::assertNothingSent();
    }

    #[Test]
    public function consent_creates_an_identifier_and_rejection_removes_it(): void
    {
        $controller = new MetaPixelConsentController();
        $accepted = $controller->store(Request::create('/tracking/meta/consent', 'POST', ['consent' => 'granted']));
        $cookies = collect($accepted->headers->getCookies())->keyBy(fn ($cookie) => $cookie->getName());
        $this->assertSame('granted', $cookies['meta_marketing_consent']->getValue());
        $this->assertNotEmpty($cookies['meta_marketing_visitor']->getValue());
        $this->assertTrue($cookies['meta_marketing_visitor']->isHttpOnly());

        $denied = $controller->store(Request::create('/tracking/meta/consent', 'POST', ['consent' => 'denied']));
        $revokeCookies = collect($denied->headers->getCookies())->keyBy(fn ($cookie) => $cookie->getName());
        $this->assertSame('denied', $revokeCookies['meta_marketing_consent']->getValue());
        $this->assertTrue($revokeCookies['meta_marketing_visitor']->getExpiresTime() < time());
    }
}
