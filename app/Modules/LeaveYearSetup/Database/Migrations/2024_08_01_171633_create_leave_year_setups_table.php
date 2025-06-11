<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveYearSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_year_setups', function (Blueprint $table) {
            $table->id();
            $table->string('leave_year')->nullable();
            $table->string('leave_year_english')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->date('start_date_english')->nullable();
            $table->date('end_date_english')->nullable();
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('leave_year_setups');
    }
}
