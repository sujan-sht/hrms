<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnJobStatusToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->integer('job_status')->nullable()->after('nationality');
            $table->integer('religion')->nullable()->after('job_status');
            $table->integer('not_affect_on_payroll')->nullable()->after('religion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('job_status');
            $table->dropColumn('religion');
            $table->dropColumn('not_affect_on_payroll');
        });
    }
}
