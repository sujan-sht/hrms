<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBranchIdToGeofenceAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('geofence_allocations', function (Blueprint $table) {
            $table->integer('branch_id')->nullable()->after('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('geofence_allocations', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
    }
}
