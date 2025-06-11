<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffboardResignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offboard_resignations', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->date('last_working_date')->nullable();
            $table->text('remark')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('offboard_resignations');
    }
}
