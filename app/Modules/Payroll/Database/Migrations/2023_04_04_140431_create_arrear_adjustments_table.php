<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrearAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrear_adjustments', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id')->comment('id from employee table');
            $table->text('name');
            $table->integer('organization_id')->default(0);
            $table->integer('branch_id')->default(0);
            $table->integer('designation_id')->default(0);
            $table->integer('emp_status')->default(0)->nullable();
            $table->decimal('arrear_amt', 10, 2)->default(0)->nullable();
            $table->date('effective_date');
            $table->string('nep_effective_date');
            $table->tinyInteger('status')->default(0);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0)->nullable();
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
        Schema::dropIfExists('arrear_adjustments');
    }
}
