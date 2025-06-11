<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedByHrColumnToPreviousJobDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('previous_job_details', function (Blueprint $table) {
            $table->tinyInteger('approved_by_hr')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('previous_job_details', function (Blueprint $table) {
            $table->dropColumn('approved_by_hr');
        });
    }
}
