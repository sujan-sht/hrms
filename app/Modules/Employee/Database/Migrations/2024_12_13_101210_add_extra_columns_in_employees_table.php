<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnsInEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('education_details', function (Blueprint $table) {
            $table->string('course_name')->nullable()->after('employee_id');
            $table->string('score')->nullable()->after('course_name');
            $table->string('division')->nullable()->after('score');
            $table->string('faculty')->nullable()->after('division');
            $table->string('specialization')->nullable()->after('faculty');
            $table->string('university_name')->nullable()->after('specialization');
            $table->text('equivalent_certificates')->nullable()->after('university_name'); // attachment of equivalent certificates : multiple
            $table->string('major_subject')->nullable()->after('equivalent_certificates');
            $table->text('degree_certificates')->after('major_subject')->nullable(); // attachment of certificate of each degree : multiple
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('education_details', function (Blueprint $table) {
            $table->dropColumn([
                'course_name',
                'score',
                'division',
                'faculty',
                'specialization',
                'university_name',
                'equivalent_certificates',
                'major_subject',
                'degree_certificates'
            ]);
        });
    }
}
