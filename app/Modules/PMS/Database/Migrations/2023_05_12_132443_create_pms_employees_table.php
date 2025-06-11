<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePmsEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pms_employees', function (Blueprint $table) {
            $table->id();
            $table->integer('fiscal_year_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('rollout_date')->nullable();
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
        Schema::dropIfExists('pms_employees');
    }
}
