<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTadaRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tada_request_details', function (Blueprint $table) {
            $table->id();

            $table->integer('tada_request_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->decimal('amount', 14 , 2)->nullable();
            $table->text('remark')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tada_request_details');
    }
}