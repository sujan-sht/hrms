<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNepaliDateAndStatusToAdvanceSettlementPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advance_settlement_payments', function (Blueprint $table) {
            $table->string('nepali_date')->nullable()->after('date');
            $table->integer('status')->default(10)->after('remark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advance_settlement_payments', function (Blueprint $table) {
            $table->dropColumn('nepali_date');
            $table->dropColumn('status');
        });
    }
}
