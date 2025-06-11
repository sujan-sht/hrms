<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeMassIncrementDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_mass_increment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_mass_increment_id');
            $table->integer('income_setup_id');
            $table->float('exiting_amount',10,2);
            $table->float('increased_amount',10,2);
            $table->string('effective_date');

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
        Schema::dropIfExists('employee_mass_increment_details');
    }
}
