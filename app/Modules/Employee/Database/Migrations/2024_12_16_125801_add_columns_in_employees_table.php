<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('ethnicity')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('gratuity_fund_account_no')->nullable();
            $table->string('telephone')->nullable();
            $table->text('languages')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['ethnicity', 'passport_no', 'gratuity_fund_account_no', 'telephone', 'languages']);
        });
    }
}
