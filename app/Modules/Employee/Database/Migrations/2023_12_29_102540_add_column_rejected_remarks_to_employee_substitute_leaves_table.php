<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnRejectedRemarksToEmployeeSubstituteLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_substitute_leaves', function (Blueprint $table) {
            $table->text('forwarded_remarks')->nullable()->after('is_expired');
            $table->text('rejected_remarks')->nullable()->after('forwarded_remarks');
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
            $table->dropColumn('forwarded_remarks');
            $table->dropColumn('rejected_remarks');
        });
    }
}
