<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvanceSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_settlements', function (Blueprint $table) {
            $table->id();
            $table->integer('advance_id');
            $table->date('due_date')->nullable();
            $table->float('amount')->nullable();
            $table->string('starting_month')->nullable();
            $table->integer('number_of_month')->nullable();
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
        Schema::dropIfExists('advance_settlements');
    }
}
