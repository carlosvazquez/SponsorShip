<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Sponsorable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SponsorableTest extends TestCase
{
    /** @test */
    public function finding_sponsorable_by_slug()
    {
        $foundSponsorable = Sponsorable::findOrFailBySlug('full-stack-radio');
        $this->assertTrue($foundSponsorable->is($sponsorable));
    }
}
