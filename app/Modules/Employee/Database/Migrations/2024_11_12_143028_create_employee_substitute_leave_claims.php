<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSubstituteLeaveClaims extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_substitute_leave_claims', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_substitute_leave_id');
            $table->text('forwarded_remarks')->nullable();
            $table->text('rejected_remarks')->nullable();
            $table->integer('forwarded_by')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->integer('accepted_by')->nullable();
            $table->integer('claim_status')->nullable();
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
        Schema::dropIfExists('employee_substitute_leave_claims');
    }
}
