<?php
namespace App\Imports;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\User\Entities\Role;
use App\Modules\User\Entities\User;
use App\Modules\User\Entities\UserRole;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Modules\Employee\Entities\Employee;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Modules\Organization\Entities\Organization;
class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $employee = Employee::where('employee_code', trim($row['employee_code']))->first();
        if (!is_null($employee)) {
            $employee->update([
                'first_name' => $row['first_name'] ?? $employee->first_name,
                'middle_name' => $row['middle_name'] ?? $employee->middle_name,
                'last_name' => $row['last_name'] ?? $employee->last_name,
                'organization_id' => $row['organization_id'] ?? $employee->organization_id ?? Organization::latest()->first()->id,
                'status' => true,
            ]);
            $role = Role::firstOrCreate([
                'user_type' => $row['role'],
            ], [
                'name' => Str::title($row['role']),
                'status' => 1,
            ]);
            $user = User::where('emp_id', $employee->id)->first();
            // $random_password = Str::random(16);
            $random_password = env('DEFAULT_USER_PASSWORD', "Cocacola@123");
            $user_data = [
                'ip_address' => Request::ip(),
                'username' => $row['user_name'],
                'password' => bcrypt($random_password),
                'email' => $employee->official_email ?? $employee->personal_email ?? null,
                'phone' => $employee->mobile ?? $employee->phone ?? null,
                'emp_id' => $employee->id,
                'user_type' => $row['role'] ?? 'employee',
                'first_name' => $row['first_name'] ?? null,
                'middle_name' => $row['middle_name'] ?? null,
                'last_name' => $row['last_name'] ?? null,
                'active' => false,
            ];
            if (!is_null($user)) {
                $user->update($user_data);
                $user = $user->refresh();
            } else {
                $user = User::create($user_data);
            }
            $user_role = UserRole::where('user_id', $user->id)->first();
            if (!is_null($user_role)) {
                DB::table('user_roles')
                    ->where('user_id', $user_role->user_id)
                    ->update(['role_id' => $role->id]);
            } else {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $role->id,
                ]);
            }
            return $user;
        }
        return null;
        /* else {
            $employee = Employee::create([
                'employee_code' => trim($row['employee_code']),
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'last_name' => $row['last_name'],
                'organization_id' => $row['organization_id'] ?? Organization::latest()->first()->id,
                'status' => true,
            ]);
        }
        $role = Role::firstOrCreate([
            'user_type' => $row['role'],
        ], [
            'name' => Str::title($row['role']),
            'status' => 1,
        ]);
        $user = User::where('emp_id', $employee->id)->first();
        // $random_password = Str::random(16);
        $random_password = env('DEFAULT_USER_PASSWORD', "Cocacola@123");
        $user_data = [
            'ip_address' => Request::ip(),
            'username' => $row['user_name'],
            'password' => bcrypt($random_password),
            'email' => $employee->official_email ?? $employee->personal_email ?? null,
            'phone' => $employee->mobile ?? $employee->phone ?? null,
            'emp_id' => $employee->id,
            'user_type' => $row['role'] ?? 'employee',
            'first_name' => $row['first_name'] ?? null,
            'middle_name' => $row['middle_name'] ?? null,
            'last_name' => $row['last_name'] ?? null,
            'active' => false,
        ];
        if (!is_null($user)) {
            $user->update($user_data);
            $user = $user->refresh();
        } else {
            $user = User::create($user_data);
        }
        $user_role = UserRole::where('user_id', $user->id)->first();
        if (!is_null($user_role)) {
            DB::table('user_roles')
                ->where('user_id', $user_role->user_id)
                ->update(['role_id' => $role->id]);
        } else {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        }
        return $user; */
    }
}
