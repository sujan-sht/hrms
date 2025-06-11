<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLetterFieldToOffboardResignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offboard_resignations', function (Blueprint $table) {
            $table->string('issued_date')->nullable()->after('status');
            $table->longText('issued_remark')->nullable()->after('issued_date');
            $table->string('received_date')->nullable()->after('issued_remark');
            $table->string('received_by')->nullable()->after('received_date');
            $table->longText('received_remark')->nullable()->after('received_by');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offboard_resignations', function (Blueprint $table) {
            $table->dropColumn('issued_date');
            $table->dropColumn('issued_remark');
            $table->dropColumn('received_date');
            $table->dropColumn('received_by');
            $table->dropColumn('received_remark');
        });
    }
}
