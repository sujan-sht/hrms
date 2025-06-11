<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettigWiseAllowanceSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settig_wise_allowance_setups', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->nullable();
            $table->integer('level_id')->nullable();
            $table->integer('designation_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->double('per_day_allowance', 16, 2)->nullable();
            $table->string('travel_setup_type');
            $table->string('type_id');
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
        Schema::dropIfExists('settig_wise_allowance_setups');
    }
}
