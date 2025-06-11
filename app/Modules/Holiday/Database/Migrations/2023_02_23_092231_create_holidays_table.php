<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            // $table->string('title')->nullable();
            $table->integer('fiscal_year_id');
            $table->integer('calendar_type')->comment('1=>BS,2=>AD')->nullable();
            $table->integer('gender_type')->comment('1=>all,2=>female,3=>male')->nullable();
            $table->integer('status')->comment('10=>Inactive,11=>Active')->nullable();
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
        Schema::dropIfExists('holidays');
    }
}
