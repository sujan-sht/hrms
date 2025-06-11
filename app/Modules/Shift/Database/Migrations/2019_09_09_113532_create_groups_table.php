<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('org_id')->default(0);
            $table->string('group_name')->nullable();
            $table->integer('shift_id')->nullable();
            $table->integer('ot_grace')->nullable();
            $table->string('ot_grace_period')->nullable();
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
        Schema::dropIfExists('shift_groups');
    }
}
