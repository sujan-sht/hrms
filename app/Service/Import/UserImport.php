<?php

namespace App\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\User\Entities\Role;
use App\Modules\User\Entities\User;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Repositories\UserRoleRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class UserImport
{

    public static function import($array)
    {
        try {
            ini_set('max_execution_time', 0);


            foreach ($array as $data) {
                if ($data[0] != null) {
                    $employee = Employee::where('employee_code', $data[0])->first();
                    if (!is_null($employee)) {
                        $oldUser = User::where('emp_id', $employee->id)->first();
                        if (is_null($oldUser)) {
                            $role = Role::where('user_type', $data[6])->first();
                            if (!is_null($role)) {
                                $userData = [
                                    'ip_address' => Request::ip(),
                                    'username' => $data[4],
                                    'password' => Hash::make($data[5]),
                                    'email' => $employee->email,
                                    'phone' => $employee->phone,
                                    'user_type' => $role->user_type,
                                    'active' => 1,
                                    'first_name' => $data[1],
                                    'middle_name' => $data[2] ?? null,
                                    'last_name' => $data[3],
                                    'emp_id' => $employee->id,
                                    'remember_token' => '$b$d' . $data[5] . '$e$p',
                                    'parent_id' => 1
                                ];
                                $user = (new UserRepository())->save($userData);

                                //Insert into User Role
                                $role_data = array(
                                    'user_id' => $user->id,
                                    'role_id' => $role->id
                                );
                                (new UserRoleRepository())->save($role_data);

                                $update_emp = array(
                                    'is_user_access' => '1',
                                    'pass_token' => $data[5],
                                );

                                (new EmployeeRepository())->update($employee->id, $update_emp);
                            }
                        }
                    }
                }
            }
            toastr()->success('Bulk Upload successfully');
        } catch (\Exception $e) {
            toastr()->error('Something went wrong!!');
        }
    }
}
