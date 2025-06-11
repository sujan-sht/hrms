<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToAttendanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_requests', function (Blueprint $table) {
            $table->dateTime('approved_date')->nullable();
            $table->dateTime('forwarded_date')->nullable();
            $table->dateTime('rejected_date')->nullable();
            $table->dateTime('cancelled_date')->nullable();
            $table->integer('forwarded_by')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->integer('cancelled_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_requests', function (Blueprint $table) {
            $table->dropColumn('approved_date');
            $table->dropColumn('forwarded_date');
            $table->dropColumn('rejected_date');
            $table->dropColumn('cancelled_date');
            $table->dropColumn('forwarded_by');
            $table->dropColumn('rejected_by');
            $table->dropColumn('cancelled_by');
        });
    }
}
