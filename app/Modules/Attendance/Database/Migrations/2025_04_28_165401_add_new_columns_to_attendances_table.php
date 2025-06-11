<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
          if (!Schema::hasColumn('attendances', 'late_arrival_in_minutes')) {
                $table->integer('late_arrival_in_minutes')->nullable()->after('checkout_original');
            }

            if (!Schema::hasColumn('attendances', 'early_departure_in_minutes')) {
                $table->integer('early_departure_in_minutes')->nullable()->after('late_arrival_in_minutes');
            }

            if (!Schema::hasColumn('attendances', 'is_checkin_next_day')) {
                $table->tinyInteger('is_checkin_next_day')->nullable()->after('late_arrival_in_minutes');
            }

            if (!Schema::hasColumn('attendances', 'checkin_ip')) {
                $table->string('checkin_ip')->nullable()->after('checkin');
            }

            if (!Schema::hasColumn('attendances', 'checkout_ip')) {
                $table->string('checkout_ip')->nullable()->after('checkout');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('late_arrival_in_minutes');
            $table->dropColumn('early_departure_in_minutes');
            $table->dropColumn('is_checkin_next_day');
            $table->dropColumn('checkin_ip');
            $table->dropColumn('checkout_ip');
        });
    }
}
