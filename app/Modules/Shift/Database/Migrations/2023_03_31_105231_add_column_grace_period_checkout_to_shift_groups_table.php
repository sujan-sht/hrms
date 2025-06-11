<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnGracePeriodCheckoutToShiftGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shift_groups', function (Blueprint $table) {
            $table->string('grace_period_checkout')->nullable()->after('ot_grace_period');
            $table->string('grace_period_checkin_for_penalty')->nullable()->after('grace_period_checkout');
            $table->string('grace_period_checkout_for_penalty')->nullable()->after('grace_period_checkin_for_penalty');
            $table->time('leave_benchmark_time_for_first_half')->nullable()->after('grace_period_checkout_for_penalty');
            $table->time('leave_benchmark_time_for_second_half')->nullable()->after('leave_benchmark_time_for_first_half');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shift_groups', function (Blueprint $table) {
            $table->dropColumn('grace_period_checkout');
            $table->dropColumn('grace_period_checkin_for_penalty');
            $table->dropColumn('grace_period_checkout_for_penalty');
            $table->dropColumn('leave_benchmark_time_for_first_half');
            $table->dropColumn('leave_benchmark_time_for_second_half');
        });
    }
}
