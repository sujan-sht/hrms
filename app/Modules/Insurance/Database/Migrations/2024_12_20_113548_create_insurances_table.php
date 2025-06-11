<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsurancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insurance_type_id');
            $table->string('policy_number')->nullable();
            $table->string('policy_start_date')->nullable();
            $table->string('policy_end_date')->nullable();
            $table->string('policy_maturity_date')->nullable();
            $table->float('sum_assured_amount', 8, 2)->nullable();
            $table->string('company_name')->nullable();
            $table->float('premium_amount', 8, 2)->nullable();
            $table->string('premium_payment_by')->nullable();
            $table->float('total_employees', 8, 2)->nullable();
            $table->float('total_employer', 8, 2)->nullable();
            $table->string('document_upload')->nullable();
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
        Schema::dropIfExists('insurances');
    }
}
