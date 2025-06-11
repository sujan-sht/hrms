<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeductionSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduction_setups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('short_name');
            $table->string('description')->nullable();
            $table->integer('method')->nullable()->comment('1 => fixed, 2 => percentage');
            $table->float('amount', 10, 2)->nullable();
            $table->float('percentage', 10, 2)->nullable();
            $table->integer('income_id')->nullable();
            $table->integer('order')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('deduction_setups');
    }
}
