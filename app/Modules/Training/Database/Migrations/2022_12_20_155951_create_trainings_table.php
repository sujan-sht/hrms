<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->integer('division_id')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('facilitator_name')->nullable();
            $table->string('facilitator')->nullable();
            $table->string('location')->nullable();
            $table->string('type')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->integer('no_of_days')->nullable();
            $table->double('planned_budget', 14, 2)->nullable();
            $table->integer('no_of_participants')->nullable();
            $table->integer('no_of_mandays')->nullable();
            $table->double('actual_expense_incurred', 14, 2)->nullable();
            $table->string('month')->nullable();
            $table->integer('no_of_employee')->nullable();
            $table->string('status')->nullable();
            $table->string('date')->nullable();
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
        Schema::dropIfExists('trainings');
    }
}
