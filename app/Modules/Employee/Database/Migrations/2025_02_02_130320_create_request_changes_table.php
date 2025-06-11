<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('old_first_name')->nullable();
            $table->string('old_middle_name')->nullable();
            $table->string('old_last_name')->nullable();
            $table->string('old_mobile')->nullable();
            $table->string('old_phone')->nullable();
            $table->string('old_personal_email')->nullable();
            $table->string('old_permanent_address')->nullable();
            $table->string('old_temporary_address')->nullable();
            $table->string('new_first_name')->nullable();
            $table->string('new_middle_name')->nullable();
            $table->string('new_last_name')->nullable();
            $table->string('new_mobile')->nullable();
            $table->string('new_phone')->nullable();
            $table->string('new_personal_email')->nullable();
            $table->string('new_permanent_address')->nullable();
            $table->string('new_temporary_address')->nullable();
            $table->string('entity')->nullable();
            $table->unsignedBigInteger('old_entity_id')->nullable();
            $table->unsignedBigInteger('new_entity_id')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('change_date')->nullable();
            $table->dateTime('approved_date')->nullable();
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
        Schema::dropIfExists('request_changes');
    }
}