<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisaAndImmigrationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visa_and_immigration_details', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->string('country')->nullable();
            $table->string('visa_type')->nullable();
            $table->date('visa_expiry_date')->nullable();
            $table->string('passport_number')->nullable();
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
        Schema::dropIfExists('visa_and_immigration_details');
    }
}
