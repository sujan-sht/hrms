<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_participants', function (Blueprint $table) {
            $table->id();
            $table->integer('poll_id')->nullable();
            $table->integer('organization_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('level_id')->nullable();
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
        Schema::dropIfExists('poll_participants');
    }
}
