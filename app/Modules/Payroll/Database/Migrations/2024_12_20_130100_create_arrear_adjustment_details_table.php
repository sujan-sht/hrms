<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrearAdjustmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrear_adjustment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('arrear_adjustment_id')->nullable();
            $table->integer('income_setup_id')->nullable();
            $table->double('arrear_amount',16,2)->nullable();
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
        Schema::dropIfExists('arrear_adjustment_details');
    }
}
