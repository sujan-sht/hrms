<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsAgreeToTadaRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tada_requests', function (Blueprint $table) {
            $table->integer('is_agree')->nullable()->after('rejected_remarks');
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
            $table->dropColumn('is_agree');
        });
    }
}
