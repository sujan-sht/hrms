<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeOffboardApprovalFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_offboard_approval_flows', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('first_approval')->comment('user_id')->nullable();
            $table->integer('last_approval')->comment('user_id')->nullable();
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
        Schema::dropIfExists('employee_offboard_approval_flows');
    }
}
