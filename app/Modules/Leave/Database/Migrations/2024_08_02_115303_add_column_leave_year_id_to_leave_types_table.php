<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLeaveYearIdToLeaveTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->integer('leave_year_id')->nullable()->after('fiscal_year_id');
        });

        Schema::table('employee_leaves', function (Blueprint $table) {
            $table->integer('leave_year_id')->nullable()->after('fiscal_year_id');
        });

        Schema::table('employee_leave_archives', function (Blueprint $table) {
            $table->integer('leave_year_id')->nullable()->after('fiscal_year_id');
        });

        Schema::table('employee_leave_openings', function (Blueprint $table) {
            $table->integer('leave_year_id')->nullable()->after('fiscal_year_id');
        });

        Schema::table('leave_encashables', function (Blueprint $table) {
            $table->integer('leave_year_id')->nullable()->after('fiscal_year_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->dropColumn('leave_year_id');
        });

        Schema::table('employee_leaves', function (Blueprint $table) {
            $table->dropColumn('leave_year_id');
        });

        Schema::table('employee_leave_archives', function (Blueprint $table) {
            $table->dropColumn('leave_year_id');
        });

        Schema::table('employee_leave_openings', function (Blueprint $table) {
            $table->dropColumn('leave_year_id');
        });

        Schema::table('leave_encashables', function (Blueprint $table) {
            $table->dropColumn('leave_year_id');
        });
    }
}
