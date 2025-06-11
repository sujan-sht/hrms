<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovalsIdTadaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tadas', function (Blueprint $table) {
            $table->integer('first_approval_id')->nullable()->after('updated_by');
            $table->integer('forwarded_to')->nullable()->comment('second approval id')->after('first_approval_id');
       
            $table->integer('fully_settled_by')->nullable()->after('forwarded_to');
            $table->date('fully_settled_date')->nullable()->after('fully_settled_by');

            $table->integer('request_closed_by')->nullable()->after('fully_settled_date');
            $table->date('request_closed_date')->nullable()->after('request_closed_by');
            $table->text('request_closed_remarks')->nullable()->after('request_closed_date');
            $table->decimal('request_closed_amt', 14, 2)->nullable()->after('request_closed_remarks');

            $table->text('rejected_remarks')->nullable()->after('request_closed_amt');
            $table->text('rejected_by')->nullable()->after('rejected_remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tadas', function (Blueprint $table) {
            $table->dropColumn('first_approval_id');
            $table->dropColumn('forwarded_to');
            $table->dropColumn('fully_settled_by');
            $table->dropColumn('fully_settled_date');

            $table->dropColumn('request_closed_by');
            $table->dropColumn('request_closed_date');
            $table->dropColumn('request_closed_remarks');
            $table->dropColumn('request_closed_amt');
            $table->dropColumn('rejected_remarks');
            $table->dropColumn('rejected_by');
        });
    }
}
