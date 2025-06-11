<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToBusinessTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_trips', function (Blueprint $table) {
            $table->string('designation')->nullable();
            $table->string('destination')->nullable();
            $table->integer('travel_type')->nullable();
            $table->text('purpose')->nullable();
            $table->string('departure')->nullable();
            $table->string('currency_type')->nullable();
            $table->string('note')->nullable();
            $table->string('quantity')->nullable();
            $table->json('foreign_currency_detail')->nullable();
            $table->string('convert_nepali_amount')->nullable();
            $table->string('document')->nullable();
            $table->integer('transport_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_trips', function (Blueprint $table) {});
    }
}
