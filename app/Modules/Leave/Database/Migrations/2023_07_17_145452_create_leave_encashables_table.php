<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveEncashablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_encashables', function (Blueprint $table) {
            $table->id();
            $table->integer('fiscal_year_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('total_leave')->nullable();
            $table->string('amt')->nullable();
            $table->date('payment_date')->nullable();
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
        Schema::dropIfExists('leave_encashables');
    }
}
