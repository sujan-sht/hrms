<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeaveKindIdToEmployeeSubstituteLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_substitute_leaves', function (Blueprint $table) {
            $table->integer('leave_kind')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_substitute_leaves', function (Blueprint $table) {
            $table->dropColumn('leave_kind');
        });
    }
}
