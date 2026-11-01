<?php

namespace Tests\Unit;

use App\Support\MetaEventId;
use Illuminate\Support\Str;
use Tests\TestCase;

class MetaEventIdTest extends TestCase
{
    public function test_same_saved_record_and_event_produce_stable_uuid(): void
    {
        $id = MetaEventId::forRecord('Lead', 'b5dc1dd0-08c2-4e7a-80d4-03133227d10d');

        $this->assertTrue(Str::isUuid($id));
        $this->assertSame($id, MetaEventId::forRecord('Lead', 'b5dc1dd0-08c2-4e7a-80d4-03133227d10d'));
    }

    public function test_different_record_or_event_never_shares_identity(): void
    {
        $lead = MetaEventId::forRecord('Lead', 42);
        $anotherLead = MetaEventId::forRecord('Lead', 43);
        $registration = MetaEventId::forRecord('CompleteRegistration', 42);

        $this->assertNotSame($lead, $anotherLead);
        $this->assertNotSame($lead, $registration);
        $this->assertNotSame($anotherLead, $registration);
    }

    public function test_only_laravel_can_emit_verified_conversions(): void
    {
        foreach (['Lead', 'lead', 'CompleteRegistration', 'SubmitApplication', 'Purchase', 'purchase'] as $event) {
            $this->assertTrue(MetaEventId::isServerConfirmed($event));
        }

        foreach (['PageView', 'ViewContent', 'CTA_Click', 'ScrollDepth', 'FormStart'] as $event) {
            $this->assertFalse(MetaEventId::isServerConfirmed($event));
        }
    }
}
