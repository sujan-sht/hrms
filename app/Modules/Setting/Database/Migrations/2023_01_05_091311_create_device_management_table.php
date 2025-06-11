<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceManagementTable extends Migration
{
    /**
     * Run the migrations.
     *    protected $fillable = ['organization_id','ip_address','port','device_id','communication_password','status'];

     * @return void
     */
    public function up()
    {
        Schema::create('device_management', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('ip_address')->nullable();
            $table->string('port')->nullable();
            $table->string('device_id')->nullable();
            $table->string('communication_password')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('device_management');
    }
}
