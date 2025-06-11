<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTravelFieldsEmployeerequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_requests', function (Blueprint $table) {

            $table->date('travel_date')->nullable()->after('account_number');
            $table->string('market_visit_location')->nullable()->after('travel_date');
            $table->string('night_halt')->nullable()->after('market_visit_location');
            $table->decimal('transport_cost', 10, 2)->nullable()->after('night_halt');
            $table->decimal('local_DA', 10, 2)->nullable()->after('transport_cost');
            $table->decimal('DA', 10, 2)->nullable()->after('local_DA');
            $table->string('telephone')->nullable()->after('DA')->comment('daily allowance');
            $table->decimal('motor_cycle_expenses', 10, 2)->nullable()->after('telephone')->comment('mis');
            $table->decimal('PR', 10, 2)->nullable()->after('motor_cycle_expenses');
            $table->decimal('lodging', 10, 2)->nullable()->after('PR');
            $table->decimal('fooding', 10, 2)->nullable()->after('lodging');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_requests', function (Blueprint $table) {
            $table->dropColumn('travel_date');
            $table->dropColumn('market_visit_location');
            $table->dropColumn('night_halt');
            $table->dropColumn('transport_cost');
            $table->dropColumn('local_DA');
            $table->dropColumn('DA');
            $table->dropColumn('telephone');
            $table->dropColumn('motor_cycle_expenses');
            $table->dropColumn('PR');
        });
    }
}
