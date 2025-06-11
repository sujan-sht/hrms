<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToEmployeeSubstituteLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_substitute_leaves', function (Blueprint $table) {
            $table->time('checkin')->nullable()->after('date');
            $table->time('checkout')->nullable()->after('checkin');
            $table->string('total_working_hr', 20)->nullable()->after('checkout');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_substitute_leaves', function (Blueprint $table) {
            $table->dropColumn([
                'checkin',
                'checkout',
                'total_working_hr',
                'attendance_synced_at'
            ]);
        });
    }
}
