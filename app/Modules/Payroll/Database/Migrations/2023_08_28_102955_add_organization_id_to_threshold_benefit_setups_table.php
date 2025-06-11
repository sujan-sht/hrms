<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrganizationIdToThresholdBenefitSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('threshold_benefit_setups', function (Blueprint $table) {
            $table->integer('organization_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('threshold_benefit_setups', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });
    }
}
