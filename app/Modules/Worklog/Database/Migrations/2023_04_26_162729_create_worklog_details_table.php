<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorklogDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worklog_details', function (Blueprint $table) {
            $table->id();
            $table->integer('worklog_id');
            $table->integer('employee_id');
            $table->string('hours')->nullable();
            // $table->integer('project_id')->nullable();
            $table->string('title');
            $table->string('priority')->nullable();
            $table->string('assigned_to')->nullable();

            $table->longText('detail')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('worklog_details');
    }
}
