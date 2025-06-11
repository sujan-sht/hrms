<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeInternationalApprovalFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_international_approval_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('first_approval_user_id')->nullable();
            $table->unsignedBigInteger('second_approval_user_id')->nullable();
            $table->unsignedBigInteger('third_approval_user_id')->nullable();
            $table->unsignedBigInteger('fourth_approval_user_id')->nullable();
            $table->unsignedBigInteger('last_approval_user_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('employee_international_approval_flows');
    }
}
