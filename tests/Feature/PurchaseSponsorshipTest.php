<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Sponsorship;
use App\Sponsorable;
use App\SponsorableSlot;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class PurchaseSponsorshipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function purchasing_available_sponsorship_slots()
    {
        $sponsorable = factory(Sponsorable::class)->create(['slug' => 'full-stack-radio']);

        $slotA = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'publish_date' => now()->addMonths(1)]);
        $slotB = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'publish_date' => now()->addMonths(2)]);
        $slotC = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'publish_date' => now()->addMonths(3)]);

        $response = $this->postJson('/full-stack-radio/sponsorships', [
            'sponsorable_slots' => [
                $slotA->getKey(),
                $slotC->getKey(),
            ]
        ]);

        $response->assertStatus(201);

        $this->assertEquals(1, Sponsorship::count());
        $sponsorship = Sponsorship::first();

        $this->assertEquals($sponsorship->getKey(), $slotA->fresh()->sponsorship_id);
        $this->assertEquals($sponsorship->getKey(), $slotC->fresh()->sponsorship_id);

        $this->assertNull($slotB->fresh()->sponsorship_id);
    }
}