<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectedDateToTadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tadas', function (Blueprint $table) {
            $table->date('forwarded_date')->nullable()->after('forwarded_to');
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
        Schema::table('tadas', function (Blueprint $table) {
            $table->dropColumn('forwarded_date');
            $table->dropColumn('rejected_date');
        });
    }
}
