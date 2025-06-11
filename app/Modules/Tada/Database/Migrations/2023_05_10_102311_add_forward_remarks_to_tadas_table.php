<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForwardRemarksToTadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tadas', function (Blueprint $table) {
            $table->longText('forwaded_remarks')->nullable()->after('forwarded_date');

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
            $table->dropColumn('forwaded_remarks');

        });
    }
}
