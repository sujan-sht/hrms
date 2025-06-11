<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayrollPreviousToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->integer('effective_fiscal_year')->nullable()->after('cit_no');
            $table->double('total_tds_paid',14,2)->nullable()->after('effective_fiscal_year');
            $table->date('grade_applicable_date')->nullable()->after('total_tds_paid');
            $table->string('grade_applicable_nep_date')->nullable()->after('grade_applicable_date');
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
            $table->dropColumn('effective_fiscal_year');
            $table->dropColumn('total_tds_paid');
            $table->dropColumn('grade_applicable_date');
            $table->dropColumn('grade_applicable_nep_date');
        });
    }
}
