<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->date('date')->nullable();
            $table->string('nepali_date')->nullable();
            $table->double('ot_time', 16,2)->nullable();
            $table->integer('status')->nullable();
            $table->double('eligible_ot_time', 16,2)->nullable();
            $table->integer('claim_status')->default(1);
            $table->text('reject_note')->nullable();
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
        Schema::dropIfExists('overtime_requests');
    }
}
