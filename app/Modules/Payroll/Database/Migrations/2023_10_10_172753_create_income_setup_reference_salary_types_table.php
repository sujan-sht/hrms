<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeSetupReferenceSalaryTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_setup_reference_salary_types', function (Blueprint $table) {
            $table->id();
            $table->integer('income_setup_id')->nullable();
            $table->float('percentage', 10, 2)->nullable();
            $table->integer('salary_type')->nullable()->comment('1 => basic, 2 => gross, 3 => grade');
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
        Schema::dropIfExists('income_setup_reference_salary_types');
    }
}
