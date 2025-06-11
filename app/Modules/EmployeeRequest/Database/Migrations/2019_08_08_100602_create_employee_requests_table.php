<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_requests', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('type_id')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->integer('status')->default(0);
            $table->integer('employee_id')->nullable();
            $table->integer('dropdown_id')->nullable();
            $table->float('cost', 10, 2)->nullable();
            $table->integer('pay_type')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('account_number')->nullable();
            $table->string('bill')->nullable();

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
        Schema::dropIfExists('employee_requests');
    }
}
