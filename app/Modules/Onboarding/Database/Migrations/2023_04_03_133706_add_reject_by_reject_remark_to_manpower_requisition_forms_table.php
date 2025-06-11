<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectByRejectRemarkToManpowerRequisitionFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manpower_requisition_forms', function (Blueprint $table) {
            $table->integer('reject_by')->nullable()->after('approved_by');
            $table->text('reject_remark')->nullable()->after('reject_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manpower_requisition_forms', function (Blueprint $table) {
            $table->dropColumn('reject_by');
            $table->dropColumn('reject_remark');
        });
    }
}
