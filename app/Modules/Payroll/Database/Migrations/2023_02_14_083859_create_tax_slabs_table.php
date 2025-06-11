<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxSlabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_slabs', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('type')->comment('un-married, married');
            $table->string('annual_income')->nullable();
            $table->float('tax_rate', 10, 2)->nullable();
            $table->float('tax_amount', 10, 2)->nullable();
            $table->float('actual_tax_amount', 10, 2)->nullable();
            $table->integer('order')->nullable();
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
        Schema::dropIfExists('tax_slabs');
    }
}
