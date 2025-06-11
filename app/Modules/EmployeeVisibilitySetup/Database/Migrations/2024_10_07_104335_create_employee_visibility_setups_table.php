<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeVisibilitySetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_visibility_setups', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('attendance')->default(true);
            $table->integer('leave')->default(true);
            $table->integer('payroll')->default(true);
            $table->integer('approval_flow')->default(true);
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
        Schema::dropIfExists('employee_visibility_setups');
    }
}
