<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMrfApprovalFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mrf_approval_flows', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->integer('first_approval_emp_id');
            $table->integer('second_approval_emp_id')->nullable();
            $table->integer('third_approval_emp_id')->nullable();
            $table->integer('fourth_approval_emp_id')->nullable();
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
        Schema::dropIfExists('mrf_approval_flows');
    }
}
