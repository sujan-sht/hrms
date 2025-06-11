<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSyncAndHostNameColumnToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('sync_host_name')->nullable()->comment('snyc domain name for organization');
            $table->boolean('sync_organization')->default(false)->comment('Set to 0 to disable or 1 to enable syncing organization data to ERP');
            $table->boolean('flag_organization')->default(true)->comment('Enable Disable organization sync option 1 for enable or 0 for disable');
            $table->boolean('sync_employee')->default(false)->comment('Set to 0 to disable or 1 to enable syncing employee data to ERP');
            $table->boolean('flag_employee')->default(true)->comment('Enable Disable employee sync option 1 for enable or 0 for disable');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('sync_host_name');
            $table->dropColumn('sync_organization');
            $table->dropColumn('flag_organization');
            $table->dropColumn('sync_employee');
            $table->dropColumn('flag_employee');
        });
    }
}
