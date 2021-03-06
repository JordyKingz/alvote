<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConferenceRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conference_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('join_code')->unique();
            $table->integer('status')->default(0);
            $table->integer('user_id')->nullable();
            $table->integer('invitations_send')->default(0);
            $table->integer('members_joined')->default(0);
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
        Schema::dropIfExists('conference_rooms');
    }
}
