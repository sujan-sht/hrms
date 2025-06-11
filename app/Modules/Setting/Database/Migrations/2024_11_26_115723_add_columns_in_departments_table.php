<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->string('short_code')->nullable();
            $table->tinyInteger('display_short_code')->nullable();
            $table->text('description')->nullable();
            $table->integer('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('short_code');
            $table->dropColumn('display_short_code');
            $table->dropColumn('description');
            $table->dropColumn('category');
        });
    }
}
