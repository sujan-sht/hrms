<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnReturnDateToAssetAllocatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_allocates', function (Blueprint $table) {
            $table->date('return_date')->nullable()->after('allocated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_allocates', function (Blueprint $table) {
            $table->dropColumn('return_date');
        });
    }
}
