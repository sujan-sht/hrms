<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('permanent_latitude')->nullable();
            $table->string('temporary_latitude')->nullable(); 
            $table->string('permanent_longitude')->nullable();
            $table->string('temporary_longitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('permanent_latitude');
            $table->dropColumn('temporary_latitude');
            $table->dropColumn('permanent_longitude');
            $table->dropColumn('temporary_longitude');
        });
    }
}
