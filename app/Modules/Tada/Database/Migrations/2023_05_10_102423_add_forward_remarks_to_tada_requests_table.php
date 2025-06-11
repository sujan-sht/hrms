<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForwardRemarksToTadaRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tada_requests', function (Blueprint $table) {
            $table->longText('forwarded_remarks')->nullable()->after('forwarded_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tada_requests', function (Blueprint $table) {
            $table->dropColumn('forwarded_remarks');
        });
    }
}
