<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInFamilyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('family_details', function (Blueprint $table) {
            $table->string('dob')->nullable();
            $table->boolean('is_emergency_contact')->default(0);
            $table->boolean('is_dependent')->default(0);
            $table->boolean('include_in_medical_insurance')->default(0);
            $table->integer('same_as_employee')->nullable()->comment('if integer value is exists then this family member address is same as employee address');
            $table->string('family_address')->nullable();
            $table->boolean('late_status')->default(0);
            $table->unsignedBigInteger('province_id')->nullable()->after('same_as_employee');
            $table->unsignedBigInteger('district_id')->nullable()->after('same_as_employee');
            $table->string('municipality')->nullable()->after('same_as_employee');
            $table->string('ward_no')->nullable()->after('same_as_employee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('family_address', function (Blueprint $table) {
            $table->dropColumn(['dob', 'is_emergency_contact', 'is_dependent', 'include_in_medical_insurance', 'same_as_employee', 'family_address', 'late_status']);
        });
        Schema::table('family_details', function (Blueprint $table) {
            $table->dropColumn(['province_id', 'district_id', 'municipality', 'ward_no']);
        });
    }
}
