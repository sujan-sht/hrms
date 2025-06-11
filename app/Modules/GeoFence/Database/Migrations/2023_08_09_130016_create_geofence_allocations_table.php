<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeofenceAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geofence_allocations', function (Blueprint $table) {
            $table->id();
            $table->integer('geofence_id')->nullable();
            $table->integer('organization_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->text('employee_id')->nullable();
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
        Schema::dropIfExists('geofence_allocations');
    }
}
