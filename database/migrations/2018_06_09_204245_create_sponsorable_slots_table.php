<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSponsorableSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsorable_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sponsorable_id');
            $table->unsignedInteger('sponsorship_id')->nullable();
            $table->dateTime('publish_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sponsorable_slots');
    }
}
