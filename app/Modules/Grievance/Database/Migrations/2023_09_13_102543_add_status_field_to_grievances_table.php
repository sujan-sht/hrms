<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFieldToGrievancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grievances', function (Blueprint $table) {
            $table->string('status')->nullable()->after('attachment');
            $table->date('resolved_date')->nullable()->after('status');
            $table->longText('remark')->nullable()->after('resolved_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grievances', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('resolved_date');
            $table->dropColumn('remark');

        });
    }
}
