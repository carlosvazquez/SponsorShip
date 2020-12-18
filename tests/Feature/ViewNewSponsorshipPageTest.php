<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Sponsorable;
use App\Sponsorship;
use App\SponsorableSlot;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class ViewNewSponsorshipPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function viewing_the_new_sponsorship_page()
    {
        $sponsorable = factory(Sponsorable::class)->create(['slug' => 'full-stack-radio']);

        $sponsorableSlots = new EloquentCollection([
            factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable]),
            factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable]),
            factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable]),
        ]);

        $response = $this->get('/full-stack-radio/sponsorships/new');

        $response->assertSuccessful();
        $response->assertTrue($response->data('sponsorable')->is($sponsorable));
        $sponsorableSlots->assertEquals($response->data('sponsorableSlots'));
    }
    /** @test */
    function sponsorable_slots_are_listed_in_chronological_order()
    {
        $sponsorable = factory(Sponsorable::class)->create(['slug' => 'full-stack-radio']);

        $slotA = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'publish_date' => now()->addDays(10)]);
        $slotB = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'publish_date' => now()->addDays(30)]);
        $slotC = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'publish_date' => now()->addDays(3)]);

        $response = $this->get('/full-stack-radio/sponsorships/new');

        $response->assertSuccessful();
        $this->assertTrue($response->data('sponsorable')->is($sponsorable));
        $this->assertCount(3, $response->data('sponsorableSlots'));
        $this->assertTrue($response->data('sponsorableSlots')[0]->is($slotC));
        $this->assertTrue($response->data('sponsorableSlots')[1]->is($slotA));
        $this->assertTrue($response->data('sponsorableSlots')[2]->is($slotB));
    }
    /** @test */
    function only_upcoming_sponsorable_slots_are_listed()
    {
        $sponsorable = factory(Sponsorable::class)->create(['slug' => 'full-stack-radio']);

        $slotA = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'publish_date' => now()->subDays(10)]);
        $slotB = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'publish_date' => now()->subDays(1)]);
        $slotC = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'publish_date' => now()->addMonths(1)]);
        $slotD = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'publish_date' => now()->addMonths(2)]);

        $response = $this->get('/full-stack-radio/sponsorships/new');

        $response->assertSuccessful();
        $this->assertTrue($response->data('sponsorable')->is($sponsorable));
        $this->assertCount(2, $response->data('sponsorableSlots'));
        $this->assertTrue($response->data('sponsorableSlots')[0]->is($slotC));
        $this->assertTrue($response->data('sponsorableSlots')[1]->is($slotD));
    }
    /** @test */
    function only_purchable_sponsorable_slots_are_listed()
    {
        $sponsorable = factory(Sponsorable::class)->create(['slug' => 'full-stack-radio']);
        $sponsorship = factory(Sponsorship::class)->create();

        $slotA = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable]);
        $slotB = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'sponsorship_id' => $sponsorship]);
        $slotC = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable, 'sponsorship_id' => $sponsorship]);
        $slotD = factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable]);

        $response = $this->get('/full-stack-radio/sponsorships/new');

        $response->assertSuccessful();
        $this->assertTrue($response->data('sponsorable')->is($sponsorable));
        $this->assertCount(2, $response->data('sponsorableSlots'));
        $this->assertTrue($response->data('sponsorableSlots')[0]->is($slotA));
        $this->assertTrue($response->data('sponsorableSlots')[1]->is($slotD));
    }
}
