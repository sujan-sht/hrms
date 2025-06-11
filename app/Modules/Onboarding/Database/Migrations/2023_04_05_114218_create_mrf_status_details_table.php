<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMrfStatusDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mrf_status_details', function (Blueprint $table) {
            $table->id();
            $table->integer('mrf_id');
            $table->integer('status')->nullable()->comment('status of mrf');
            $table->integer('action_by')->nullable()->comment('action taken employee');
            $table->string('action_datetime')->nullable()->comment('action taken datetime');
            $table->text('action_remark')->nullable()->comment('action taken remark');
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
        Schema::dropIfExists('mrf_status_details');
    }
}
