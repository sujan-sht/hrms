<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTadaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tada_details', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('tada_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->decimal('amount', 14 , 2)->nullable();

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
        Schema::dropIfExists('tada_details');
    }
}
