<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationalStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizational_structures', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('kra')->nullable();
            $table->text('kpi')->nullable();
            $table->text('designation')->nullable();
            $table->text('job_role')->nullable();
            $table->integer('root_employee_id')->nullable();
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
        Schema::dropIfExists('organizational_structures');
    }
}
