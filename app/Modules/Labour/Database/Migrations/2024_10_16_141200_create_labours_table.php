<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaboursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labours', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('pan_no')->nullable();
            $table->integer('skill_type')->nullable();
            $table->date('join_date')->nullable();
            $table->integer('organization');
            $table->tinyInteger('is_archived')->default(0);
            $table->date('archived_date')->nullable();
            $table->text('reason')->nullable();
            $table->text('other_desc')->nullable();
            $table->string('attachment')->nullable();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('labours');
    }
}
