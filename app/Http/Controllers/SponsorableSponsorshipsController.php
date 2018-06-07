<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SponsorableSponsorshipsController extends Controller
{
    public function new($slug)
    {
        $sponsorable = Sponsorable::findOrFailBySlig($slug);
        $sponsorableSlots = $sponsorable->slots;
        return view('sponsorable-sponsorships.new', [
            'sponsorableSlots' => $sponsorableSlots,
            ]);
    }
}
