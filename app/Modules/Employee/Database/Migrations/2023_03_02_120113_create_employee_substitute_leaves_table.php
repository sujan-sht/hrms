<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSubstituteLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_substitute_leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('date')->nullable();
            $table->text('nepali_date')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('employee_substitute_leaves');
    }
}
