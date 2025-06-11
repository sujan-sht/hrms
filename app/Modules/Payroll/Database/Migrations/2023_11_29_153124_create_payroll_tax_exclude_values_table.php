<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollTaxExcludeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_tax_exclude_values', function (Blueprint $table) {
            $table->id();
            $table->integer('payroll_id');
            $table->integer('payroll_employee_id');
            $table->integer('tax_exclude_setup_id');
            $table->float('value');
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
        Schema::dropIfExists('payroll_tax_exclude_values');
    }
}
