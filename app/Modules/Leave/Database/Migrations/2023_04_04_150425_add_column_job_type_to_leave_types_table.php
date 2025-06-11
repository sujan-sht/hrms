<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnJobTypeToLeaveTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->integer('job_type')->nullable()->after('status');
            $table->integer('contract_type')->nullable()->after('job_type');
            $table->double('fixed_remaining_leave', 8, 2)->nullable()->after('contract_type');
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
            $table->dropColumn('job_type');
            $table->dropColumn('contract_type');
            $table->dropColumn('fixed_remaining_leave');
        });
    }
}
