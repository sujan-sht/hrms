<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFieldToTrainingAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_attendances', function (Blueprint $table) {
            $table->integer('status')->nullable()->after('rating');
            // $table->longText('remarks')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_attendances', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
