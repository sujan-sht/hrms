<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeTaxExcludeSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_tax_exclude_setups', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('organization_id')->nullable();
            $table->integer('tax_exclude_setup_id')->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('employee_tax_exclude_setups');
    }
}
