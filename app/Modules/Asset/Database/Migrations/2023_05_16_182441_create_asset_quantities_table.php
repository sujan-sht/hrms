<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_quantities', function (Blueprint $table) {
            $table->id();
            $table->integer('asset_id')->nullable();
            $table->string('code')->nullable();
            $table->double('quantity', 10, 2)->nullable();
            $table->double('remaining_quantity', 10, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('asset_quantities');
    }
}
