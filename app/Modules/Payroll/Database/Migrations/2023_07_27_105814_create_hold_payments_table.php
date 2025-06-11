<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoldPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hold_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->default(0);
            $table->integer('employee_id')->default(0)->comment('id from employment table');
            $table->string('calendar_type')->default('nep');
            $table->string('year');
            $table->string('month');
            $table->text('notes')->nullable();
            
            $table->tinyInteger('is_released')->default(0)->nullable();
            $table->string('released_year')->nullable();
            $table->string('released_month')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('hold_payments');
    }
}
