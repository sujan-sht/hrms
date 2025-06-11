<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStartTimeToOvertimeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overtime_requests', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('nepali_date');
            $table->time('end_time')->nullable()->after('start_time');

            $table->string('forwarded_remarks')->nullable()->after('remarks');
            $table->integer('forwarded_by')->nullable()->after('forwarded_remarks');
            $table->date('forwarded_date')->nullable()->after('forwarded_by');

            $table->string('approved_remarks')->nullable()->after('forwarded_date');
            $table->integer('approved_by')->nullable()->after('approved_remarks');
            $table->date('approved_date')->nullable()->after('approved_by');

            $table->string('rejected_remarks')->nullable()->after('approved_date');
            $table->integer('rejected_by')->nullable()->after('rejected_remarks');
            $table->date('rejected_date')->nullable()->after('rejected_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overtime_requests', function (Blueprint $table) {
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');

            $table->dropColumn('forwarded_remarks');
            $table->dropColumn('forwarded_by');
            $table->dropColumn('forwarded_date');

            $table->dropColumn('approved_remarks');
            $table->dropColumn('approved_by');
            $table->dropColumn('approved_date');

            $table->dropColumn('rejected_remarks');
            $table->dropColumn('rejected_by');
            $table->dropColumn('rejected_date');

        });
    }
}
