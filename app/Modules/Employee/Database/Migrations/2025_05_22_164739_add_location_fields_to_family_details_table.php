<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationFieldsToFamilyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('family_details', function (Blueprint $table) {
        $table->unsignedBigInteger('province_id')->nullable()->after('is_nominee_detail');
            $table->unsignedBigInteger('district_id')->nullable()->after('province_id');
            $table->string('municipality')->nullable()->after('district_id');
            $table->string('ward_no')->nullable()->after('municipality');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('family_details', function (Blueprint $table) {
        $table->dropColumn(['province_id', 'district_id', 'municipality', 'ward_no']);
        });
    }
}
