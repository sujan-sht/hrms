<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStopPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stop_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->integer('employee_id')->default(0)->comment('id from employment table');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('nep_from_date')->nullable();
            $table->string('nep_to_date')->nullable();
            $table->tinyInteger('exclude_ssf')->default(0);
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('stop_payments');
    }
}
