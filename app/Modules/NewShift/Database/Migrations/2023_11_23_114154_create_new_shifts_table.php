<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            // $table->string('custom_title')->nullable();
            $table->string('start_time');
            $table->string('end_time');
            $table->integer('ot_grace')->nullable();
            $table->string('grace_period_checkin')->nullable();
            $table->string('grace_period_checkout')->nullable();
            $table->string('grace_period_checkin_for_penalty')->nullable();
            $table->string('grace_period_checkout_for_penalty')->nullable();
            $table->time('leave_benchmark_time_for_first_half')->nullable();
            $table->time('leave_benchmark_time_for_second_half')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('new_shifts');
    }
}
