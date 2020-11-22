<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_votes', function (Blueprint $table) {
            $table->id();
            $table->integer('answer_id');
            $table->integer('type')->default(0); // 1 agree, 2 once if, 3 disagree
            $table->string('once_if')->nullable();
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
        Schema::dropIfExists('member_votes');
    }
}
