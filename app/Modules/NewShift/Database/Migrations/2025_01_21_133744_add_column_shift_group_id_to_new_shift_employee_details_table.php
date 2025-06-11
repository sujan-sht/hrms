<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnShiftGroupIdToNewShiftEmployeeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_shift_employee_details', function (Blueprint $table) {
            $table->integer('shift_group_id')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_shift_employee_details', function (Blueprint $table) {
            $table->dropColumn('shift_group_id');
        });
    }
}
