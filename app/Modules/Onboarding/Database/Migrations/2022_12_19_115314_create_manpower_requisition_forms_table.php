<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManpowerRequisitionFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manpower_requisition_forms', function (Blueprint $table) {
            $table->id();
            $table->string('organization_id');
            $table->string('reference_number');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->longText('specification')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('division')->nullable();
            $table->integer('department')->nullable();
            $table->integer('designation')->nullable();
            $table->integer('type')->nullable();
            $table->string('position')->nullable();
            $table->integer('reporting_to')->nullable();
            $table->string('age')->nullable();                        
            $table->string('salary')->nullable();
            $table->string('experience')->nullable();
            $table->integer('two_wheeler_status')->nullable();
            $table->integer('four_wheeler_status')->nullable();
            $table->integer('prepared_by')->nullable();
            $table->integer('first_recommended_by')->nullable();
            $table->integer('second_recommended_by')->nullable();
            $table->integer('third_recommended_by')->nullable();
            $table->integer('fourth_recommended_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('manpower_requisition_forms');
    }
}
