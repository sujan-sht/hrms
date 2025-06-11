<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColInEmployeeApprovalFlows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_approval_flows', function (Blueprint $table) {
            $table->integer('second_approval_user_id')->nullable()->after('first_approval_user_id');
            $table->integer('third_approval_user_id')->nullable()->after('second_approval_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_approval_flows', function (Blueprint $table) {
            $table->dropColumn('second_approval_user_id');
            $table->dropColumn('third_approval_user_id');
        });
    }
}
