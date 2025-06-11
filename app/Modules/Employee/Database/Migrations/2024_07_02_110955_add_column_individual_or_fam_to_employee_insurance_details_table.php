<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIndividualOrFamToEmployeeInsuranceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_insurance_details', function (Blueprint $table) {
            $table->string('individual_or_fam')->nullable()->after('gmi_enable');
            $table->boolean('spouse')->change();
            $table->boolean('kid_one')->change();
            $table->boolean('kid_two')->change();
            $table->boolean('mom')->change();
            $table->boolean('dad')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_insurance_details', function (Blueprint $table) {
            $table->dropColumn('individual_or_fam');
            $table->double('spouse', 14,2)->change();
            $table->double('kid_one', 14,2)->change();
            $table->double('kid_two', 14,2)->change();
            $table->double('mom', 14,2)->change();
            $table->double('dad', 14,2)->change();
        });
    }
}
