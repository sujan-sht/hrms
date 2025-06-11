<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnRejectedRemarksToTadaRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tada_requests', function (Blueprint $table) {
            $table->integer('forwarded_to')->nullable()->comment('last approval id')->after('status');
            $table->date('forwarded_date')->nullable()->after('forwarded_to');
            $table->integer('accepted_by')->nullable()->after('forwarded_date');
            $table->date('accepted_date')->nullable()->after('accepted_by');
            $table->integer('fully_settled_by')->nullable()->after('accepted_date');
            $table->date('fully_settled_date')->nullable()->after('fully_settled_by');
            $table->integer('rejected_by')->nullable()->after('fully_settled_date');
            $table->date('rejected_date')->nullable()->after('rejected_by');
            $table->text('rejected_remarks')->nullable()->after('rejected_date');
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
            $table->dropColumn('forwarded_to');
            $table->dropColumn('forwarded_date');
            $table->dropColumn('accepted_by');
            $table->dropColumn('accepted_date');
            $table->dropColumn('fully_settled_by');
            $table->dropColumn('fully_settled_date');
            $table->dropColumn('rejected_by');
            $table->dropColumn('rejected_date');
            $table->dropColumn('rejected_remarks');
        });
    }
}
