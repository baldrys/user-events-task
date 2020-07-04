<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_events', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('participant_id')
                ->nullable(true)
                ->unsigned();
            $table->foreign('participant_id')
                ->references('id')->on('participants')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('event_id')
                ->nullable(true)
                ->unsigned();
            $table->foreign('event_id')
                ->references('id')->on('events')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('participant_events');
    }
}
