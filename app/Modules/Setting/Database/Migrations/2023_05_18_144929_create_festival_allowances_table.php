<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFestivalAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('festival_allowances', function (Blueprint $table) {
            $table->id();
            $table->integer('method')->nullable()->comment('1 => fixed, 2 => percentage');
            $table->float('amount', 10, 2)->nullable();
            $table->float('percentage', 10, 2)->nullable();
            $table->integer('salary_type')->nullable()->comment('1 => basic, 2 => gross');
            $table->integer('eligible_month')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('festival_allowances');
    }
}
