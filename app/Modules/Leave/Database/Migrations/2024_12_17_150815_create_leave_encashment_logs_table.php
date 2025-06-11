<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveEncashmentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_encashment_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->integer('leave_type_id')->nullable();
            $table->float('encashment_threshold', 8,2)->nullable();
            $table->float('leave_remaining', 8,2)->nullable();
            $table->float('exceeded_balance')->nullable();
            $table->float('total_balance')->nullable();
            $table->float('eligible_encashment')->nullable();
            $table->date('encashed_date')->nullable();
            $table->double('encashed_amount', 16,2)->nullable();
            $table->integer('status')->nullable();
            $table->integer('is_valid')->nullable();
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
        Schema::dropIfExists('leave_encashment_logs');
    }
}
