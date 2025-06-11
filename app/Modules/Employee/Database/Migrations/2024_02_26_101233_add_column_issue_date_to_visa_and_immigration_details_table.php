<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIssueDateToVisaAndImmigrationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visa_and_immigration_details', function (Blueprint $table) {
            $table->date('issued_date')->nullable()->after('visa_expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visa_and_immigration_details', function (Blueprint $table) {
            $table->dropColumn('issued_date');
        });
    }
}
