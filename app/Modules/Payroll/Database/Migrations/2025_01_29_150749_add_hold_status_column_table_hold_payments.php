<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHoldStatusColumnTableHoldPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hold_payments', function (Blueprint $table) {
            $table->enum('hold_status',[1,2,3])->default(1);
            $table->string('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hold_payments', function (Blueprint $table) {
            $table->dropColumn('hold_status');
            // $table->dropColumn('created_by');
        });
    }
}
