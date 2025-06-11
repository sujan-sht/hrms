<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToRequestChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_changes', function (Blueprint $table) {
            $table->string('old_national_id')->nullable();
            $table->string('old_passport_no')->nullable();
            $table->string('old_telephone')->nullable();
            $table->string('old_official_email')->nullable();
            $table->string('old_marital_status')->nullable();
            $table->string('old_citizenship_no')->nullable();
            $table->string('old_blood_group')->nullable();
            $table->string('old_ethnicity')->nullable();
            $table->string('old_language')->nullable();
            $table->string('new_national_id')->nullable();
            $table->string('new_passport_no')->nullable();
            $table->string('new_telephone')->nullable();
            $table->string('new_official_email')->nullable();
            $table->string('new_marital_status')->nullable();
            $table->string('new_citizenship_no')->nullable();
            $table->string('new_blood_group')->nullable();
            $table->string('new_ethnicity')->nullable();
            $table->string('new_language')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_changes', function (Blueprint $table) {
            $table->dropColumn([
                'old_national_id',
                'old_passport_no',
                'old_telephone',
                'old_official_email',
                'old_marital_status',
                'old_citizenship_no',
                'old_blood_group',
                'old_ethnicity',
                'old_language',
                'new_national_id',
                'new_passport_no',
                'new_telephone',
                'new_official_email',
                'new_marital_status',
                'new_citizenship_no',
                'new_blood_group',
                'new_ethnicity',
                'new_language'
            ]);
        });
    }
}
