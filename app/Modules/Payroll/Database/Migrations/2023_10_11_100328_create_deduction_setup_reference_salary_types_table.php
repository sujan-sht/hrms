<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeductionSetupReferenceSalaryTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduction_setup_reference_salary_types', function (Blueprint $table) {
            $table->id();
            $table->integer('deduction_setup_id')->nullable();
            $table->float('percentage', 10, 2)->nullable();
            $table->integer('income_id')->nullable();
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
        Schema::dropIfExists('deduction_setup_reference_salary_types');
    }
}
