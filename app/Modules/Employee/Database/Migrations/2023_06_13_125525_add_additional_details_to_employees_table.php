<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalDetailsToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('pan_no')->nullable()->after('dob');
            $table->string('pf_no')->nullable()->after('pan_no');
            $table->string('ssf_no')->nullable()->after('pf_no');
            $table->string('cit_no')->nullable()->after('ssf_no');
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
            $table->dropColumn('pan_no');
            $table->dropColumn('pf_no');
            $table->dropColumn('ssf_no');
            $table->dropColumn('cit_no');
        });
    }
}
