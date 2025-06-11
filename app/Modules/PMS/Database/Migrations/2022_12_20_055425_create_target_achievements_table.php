<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_achievements', function (Blueprint $table) {
            $table->id();
            $table->integer('kra_id')->nullable();
            $table->integer('kpi_id')->nullable();
            $table->integer('target_id')->nullable();
            $table->integer('quarter')->nullable();
            $table->double('target_value', 14, 2)->nullable();
            $table->double('achieved_value', 14, 2)->nullable();
            $table->double('achieved_percent', 14, 2)->nullable();
            $table->double('score', 14, 2)->nullable();
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
        Schema::dropIfExists('target_achievements');
    }
}
