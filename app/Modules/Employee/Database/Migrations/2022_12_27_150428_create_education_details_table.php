<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEducationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education_details', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->string('type_of_institution')->nullable();
            $table->string('institution_name')->nullable();
            $table->string('affiliated_to')->nullable();
            $table->date('attended_from')->nullable();
            $table->date('attended_to')->nullable();
            $table->string('passed_year')->nullable();
            $table->string('level')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('education_details');
    }
}
