<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->nullable();
            $table->integer('fiscal_year_id')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->tinyInteger('leave_type')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->tinyInteger('marital_status')->nullable();
            $table->integer('number_of_days')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('show_on_employee')->nullable();
            $table->tinyInteger('prorata_status')->nullable();
            $table->tinyInteger('encashable_status')->nullable();
            $table->integer('max_encashable_days')->nullable();
            $table->tinyInteger('half_leave_status')->nullable();
            $table->tinyInteger('carry_forward_status')->nullable();
            $table->tinyInteger('sandwitch_rule_status')->nullable();
            $table->integer('pre_inform_days')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('leave_types');
    }
}
