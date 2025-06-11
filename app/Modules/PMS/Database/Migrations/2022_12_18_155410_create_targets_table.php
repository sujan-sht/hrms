<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->integer('kra_id')->nullable();
            $table->integer('kpi_id')->nullable();
            $table->string('title')->nullable();
            $table->string('frequency')->nullable();
            $table->string('category')->nullable();
            $table->float('weightage',10,2)->nullable();
            $table->integer('no_of_quarter')->nullable();
            $table->string('date')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('targets');
    }
}
