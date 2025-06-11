<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_consumptions', function (Blueprint $table) {
            $table->id();
            $table->string('starting_place')->nullable();
            $table->string('destination_place')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->double('start_km', 14,2)->nullable();
            $table->double('end_km', 14,2)->nullable();
            $table->double('km_travelled', 14,2)->nullable();
            $table->text('purpose')->nullable();
            $table->double('parking_cost', 14,2)->nullable();
            $table->string('status')->nullable();
            $table->string('created_by')->nullable();
            $table->string('verified_by')->nullable();
            $table->string('verified_at')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('approved_at')->nullable();
            $table->date('fuel_consump_created_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fuel_consumptions');
    }
}
