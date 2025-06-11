<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovalsIdEmployeerequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_requests', function (Blueprint $table) {
            $table->integer('first_approval_id')->nullable()->after('created_by');
            $table->integer('forwarded_to')->nullable()->comment('second approval id')->after('first_approval_id');
            $table->integer('approved_by')->nullable()->after('forwarded_to');
            $table->date('approved_date')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_requests', function (Blueprint $table) {
            $table->dropColumn('first_approval_id');
            $table->dropColumn('forwarded_to');
            $table->dropColumn('approved_by');
            $table->dropColumn('approved_date');
        });
    }
}
