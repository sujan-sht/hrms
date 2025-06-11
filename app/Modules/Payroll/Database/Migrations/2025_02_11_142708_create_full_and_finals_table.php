<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFullAndFinalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('full_and_finals', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->longText('form_data');
            $table->string('fine_penalty')->default(0);
            $table->string('adjustment')->default(0);
            $table->longText('remarks')->nullable();
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
        Schema::dropIfExists('full_and_finals');
    }
}
