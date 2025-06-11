<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInNewEmployeeCareerMobilityTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_employee_career_mobility_timelines', function (Blueprint $table) {
            $table->string('title')->nullable()->after('event_date');
            $table->string('icon')->nullable()->after('title');
            $table->string('color')->nullable()->after('icon');
            $table->string('career_mobility_type')->nullable()->after('color');
            $table->unsignedBigInteger('career_mobility_type_id')->nullable()->after('career_mobility_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_employee_career_mobility_timelines', function (Blueprint $table) {
            $table->dropColumn(['title', 'icon', 'color', 'career_mobility_type', 'career_mobility_type_id']);
        });
    }
}
