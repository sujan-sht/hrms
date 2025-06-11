<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFieldsToLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->integer('forward_by')->nullable()->after('status');
            $table->text('forward_message')->nullable()->after('forward_by');
            $table->integer('reject_by')->nullable()->after('forward_message');
            $table->text('reject_message')->nullable()->after('reject_by');
            $table->integer('accept_by')->nullable()->after('reject_message');
            $table->text('accept_message')->nullable()->after('accept_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('forward_by');
            $table->dropColumn('forward_message');
            $table->dropColumn('reject_by');
            $table->dropColumn('reject_message');
            $table->dropColumn('accept_by');
            $table->dropColumn('accept_message');
        });
    }
}
