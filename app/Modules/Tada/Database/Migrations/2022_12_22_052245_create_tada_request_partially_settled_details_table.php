<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTadaRequestPartiallySettledDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tada_request_partially_settled_details', function (Blueprint $table) {
            $table->id();
            $table->integer('tada_request_id');
            $table->integer('settled_by')->nullable();
            $table->date('settled_date')->nullable();
            $table->decimal('settled_amt', 14, 2)->nullable();
            $table->string('settled_method')->nullable();
            $table->string('settled_remarks')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('tada_request_partially_settled_details');
    }
}
