<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTravelExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->integer('employee_id')->nullable();
        });

        DB::statement("ALTER TABLE travel_expenses MODIFY employee_name VARCHAR(255) NULL");
    }

    public function down(): void
    {
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });

    }
}
