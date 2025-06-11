<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTadaRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tada_requests', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('request_code', 30);
            $table->integer('approver_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->string('nep_request_date')->nullable();
            $table->date('eng_request_date')->nullable();
          
            $table->text('remarks')->nullable();
            $table->string('status')->default('pending')->comment('pending, approved, rejected');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

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
        Schema::dropIfExists('tada_requests');
    }
}