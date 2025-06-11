<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiscal_year_setups', function (Blueprint $table) {
            $table->index('status');
            $table->index('end_date_english');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->index(['status', 'name']);
        });

        Schema::table('dropdowns', function (Blueprint $table) {
            $table->index('fid');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->index('organization_id');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->index('status');
            $table->index('organization_id');
            $table->index('branch_id');
            $table->index('department_id');
            $table->index('join_date');
        });

        Schema::table('employee_shifts', function (Blueprint $table) {
            $table->index(['employee_id', 'shift_id']);
            $table->index('group_id');
        });

        Schema::table('employee_day_offs', function (Blueprint $table) {
            $table->index('employee_id');
        });
        
        Schema::table('employee_approval_flows', function (Blueprint $table) {
            $table->index('employee_id');
        });

        Schema::table('leave_types', function (Blueprint $table) {
            $table->index('fiscal_year_id');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->index('employee_id');
            $table->index('parent_id');
            $table->index('leave_type_id');
        });

        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->index(['date', 'biometric_emp_id']);
            $table->index('time');
            $table->index('emp_id');
            $table->index('inout_mode');
            $table->index('org_id');
            $table->index('ip_address');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->index('emp_id');
            $table->index('org_id');
            $table->index('date');
            $table->index('checkin');
        });
       
        Schema::table('holidays', function (Blueprint $table) {
            $table->index('organization_id');
        });
               
        Schema::table('events', function (Blueprint $table) {
            $table->index(['event_start_date', 'deleted_at']);
        });

        Schema::table('notices', function (Blueprint $table) {
            $table->index('notice_date');
        });

        Schema::table('applicants', function (Blueprint $table) {
            $table->index('source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fiscal_year_setups', function (Blueprint $table) {
            $table->dropIndex('fiscal_year_setups_status_index');
            $table->dropIndex('fiscal_year_setups_end_date_english_index');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->dropIndex(['status_name']);
        });

        Schema::table('dropdowns', function (Blueprint $table) {
            $table->dropIndex('dropdowns_fid_index');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->dropIndex('branches_organization_id_index');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('employees_status_index');
            $table->dropIndex('employees_organization_id_index');
            $table->dropIndex('employees_branch_id_index');
            $table->dropIndex('employees_department_id_index');
            $table->dropIndex('employees_join_date_index');
        });

        Schema::table('employee_shifts', function (Blueprint $table) {
            $table->dropIndex(['employee_id_shift_id']);
            $table->dropIndex('employee_shifts_group_id_index');
        });

        Schema::table('employee_day_offs', function (Blueprint $table) {
            $table->dropIndex('employee_day_offs_employee_id_index');
        });

        Schema::table('employee_approval_flows', function (Blueprint $table) {
            $table->dropIndex('employee_approval_flows_employee_id_index');
        });

        Schema::table('leave_types', function (Blueprint $table) {
            $table->dropIndex('leave_types_fiscal_year_id_index');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->dropIndex('leaves_employee_id_index');
            $table->dropIndex('leaves_parent_id_index');
            $table->dropIndex('leaves_leave_type_id_index');
        });

        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropIndex(['date_biometric_emp_id']);
            $table->dropIndex('attendance_logs_time_index');
            $table->dropIndex('attendance_logs_emp_id_index');
            $table->dropIndex('attendance_logs_inout_mode_index');
            $table->dropIndex('attendance_logs_org_id_index');
            $table->dropIndex('attendance_logs_ip_address_index');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('attendances_emp_id_index');
            $table->dropIndex('attendances_org_id_index');
            $table->dropIndex('attendances_date_index');
            $table->dropIndex('attendances_checkin_index');
        });

        Schema::table('holidays', function (Blueprint $table) {
            $table->dropIndex('holidays_organization_id_index');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['event_start_date_deleted_at']);
        });

        Schema::table('notices', function (Blueprint $table) {
            $table->dropIndex('notices_notice_date_index');
        });

        Schema::table('applicants', function (Blueprint $table) {
            $table->dropIndex('applicants_source_index');
        });
    }
}
