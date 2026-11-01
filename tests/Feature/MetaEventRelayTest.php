<?php

namespace Tests\Feature;

use App\Services\MetaConversionsService;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class MetaEventRelayTest extends TestCase
{
    private function sample(string $name, string $id): array
    {
        return [
            'event_name' => $name,
            'event_id' => $id,
            'event_time' => now()->timestamp,
            'event_source_url' => 'http://localhost/service',
        ];
    }

    public function test_client_cannot_relay_unconfirmed_lead_registration_or_purchase(): void
    {
        $fake = Mockery::mock(MetaConversionsService::class);
        $fake->shouldNotReceive('queueBrowserEvents');
        $this->app->instance(MetaConversionsService::class, $fake);

        $this->withCookie('meta_marketing_consent', 'granted')->postJson(route('tracking.meta.events'), [
            'events' => [
                $this->sample('Lead', '01928b55-a708-4b3a-96cb-ece10006be01'),
                $this->sample('CompleteRegistration', '01928b55-a708-4b3a-96cb-ece10006be02'),
                $this->sample('Purchase', '01928b55-a708-4b3a-96cb-ece10006be03'),
                $this->sample('SubmitApplication', '01928b55-a708-4b3a-96cb-ece10006be04'),
                $this->sample('purchase', '01928b55-a708-4b3a-96cb-ece10006be05'),
            ],
        ])->assertStatus(202)->assertJson(['accepted' => false, 'event_count' => 0]);
    }

    public function test_duplicate_relay_ids_are_sent_once_and_other_events_continue(): void
    {
        $captured = [];
        $fake = Mockery::mock(MetaConversionsService::class);
        $fake->shouldReceive('queueBrowserEvents')->once()->andReturnUsing(
            function (array $events, Request $request) use (&$captured): void {
                $captured = $events;
            }
        );
        $this->app->instance(MetaConversionsService::class, $fake);

        $id = '01928b55-a708-4b3a-96cb-ece10006be15';
        $this->withCookie('meta_marketing_consent', 'granted')->postJson(route('tracking.meta.events'), [
            'events' => [
                $this->sample('CTA_Click', $id),
                $this->sample('CTA_Click', $id),
                $this->sample('Purchase', '01928b55-a708-4b3a-96cb-ece10006be16'),
            ],
        ])->assertStatus(202)->assertJson(['accepted' => true, 'event_count' => 1]);

        $this->assertCount(1, $captured);
        $this->assertSame('CTA_Click', $captured[0]['event_name']);
        $this->assertSame($id, $captured[0]['event_id']);
    }

    public function test_missing_marketing_consent_does_not_relay_any_event(): void
    {
        $fake = Mockery::mock(MetaConversionsService::class);
        $fake->shouldNotReceive('queueBrowserEvents');
        $this->app->instance(MetaConversionsService::class, $fake);

        $this->postJson(route('tracking.meta.events'), [
            'events' => [$this->sample('CTA_Click', '01928b55-a708-4b3a-96cb-ece10006be20')],
        ])->assertStatus(202)->assertJson(['accepted' => false, 'reason' => 'consent_required']);
    }
}
