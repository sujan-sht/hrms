<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMissionVisionToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->longText('vision')->nullable();
            $table->longText('mission')->nullable();
            $table->longText('code_of_conduct')->nullable();
            $table->string('letter_head')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('vision');
            $table->dropColumn('mission');
            $table->dropColumn('code_of_conduct');
            $table->dropColumn('letter_head');
        });
    }
}
