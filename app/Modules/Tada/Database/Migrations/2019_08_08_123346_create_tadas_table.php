<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tadas', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->integer('employee_id')->nullable();
            $table->string('nep_from_date')->nullable();
            $table->string('nep_to_date')->nullable();
            $table->date('eng_from_date')->nullable();
            $table->date('eng_to_date')->nullable();

            $table->text('excel_file')->nullable();
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
        Schema::dropIfExists('tadas');
    }
}
