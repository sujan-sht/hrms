<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAcademicQualificationToApplicantsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string("academic_qualification")->nullable()->after("external_comment");
            $table->string("current_organization")->nullable()->after("academic_qualification");
            $table->string("current_designation")->nullable()->after("current_organization");
            $table->string("reference_name")->nullable()->after("current_designation");
            $table->string("reference_position")->nullable()->after("reference_name");
            $table->string("reference_contact_number")->nullable()->after("reference_position");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn("academic_qualification");
            $table->dropColumn("current_organization");
            $table->dropColumn("current_designation");
            $table->dropColumn("reference_name");
            $table->dropColumn("reference_position");
            $table->dropColumn("reference_contact_number");
        });
    }
}
