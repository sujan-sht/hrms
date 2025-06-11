<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnInFamilyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('family_details', function (Blueprint $table) {
        //     $table->unsignedBigInteger('province_id')->nullable()->after('same_as_employee');
        //     $table->unsignedBigInteger('district_id')->nullable()->after('same_as_employee');
        //     $table->string('municipality')->nullable()->after('same_as_employee');
        //     $table->string('ward_no')->nullable()->after('same_as_employee');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('family_details', function (Blueprint $table) {
        //     $table->dropColumn(['province_id', 'district_id', 'municipality', 'ward_no']);
        // });
    }
}
