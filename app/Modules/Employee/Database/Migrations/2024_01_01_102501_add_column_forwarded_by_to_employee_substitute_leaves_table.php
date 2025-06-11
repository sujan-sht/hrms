<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnForwardedByToEmployeeSubstituteLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_substitute_leaves', function (Blueprint $table) {
            $table->integer('forwarded_by')->nullable()->after('rejected_remarks');
            $table->integer('rejected_by')->nullable()->after('forwarded_by');
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
            $table->dropColumn('forwarded_by');
            $table->dropColumn('rejected_by');
        });
    }
}
