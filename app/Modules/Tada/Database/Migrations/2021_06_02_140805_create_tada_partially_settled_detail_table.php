<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTadaPartiallySettledDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tada_partially_settled_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tada_id');
            $table->integer('settled_by')->nullable();
            $table->date('settled_date')->nullable();
            $table->decimal('settled_amt', 14, 2)->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('tada_partially_settled_detail');
    }
}
