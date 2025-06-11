<?php

namespace App\Modules\User\Repositories;

use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\LoginLogoutLog;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserRepository implements UserInterface
{

    public function find($id)
    {
        return User::find($id);
    }

    public function save($data)
    {
        return User::create($data);
    }

    public function update($id, $data)
    {
        $User = User::find($id);
        return $User->update($data);
    }

    public function delete($id)
    {
        return User::destroy($id);
    }

    public function deleteEmpUser($id)
    {
        return User::where('emp_id', '=', $id)->delete();
    }

    public function checkUsername($username)
    {
        return User::where('username', '=', $username)->get();
    }

    public function othersUsername($username, $userid)
    {
        return User::where('username', '=', $username)->where('id', '!=', $userid)->get();
    }

    public function getUserByUsername($username)
    {
        return User::where('username', '=', $username)->first();
    }

    public function getUserId($emp_id)
    {
        return User::where('emp_id', '=', $emp_id)->first();
    }

    public function getEmployeeList()
    {
        $employer_user = $this->getAll();
        $employee_data = array();
        foreach ($employer_user as $key => $user) {
            if ($user->emp_id != null) {
                if (!empty($user->middle_name)) {
                    $full_name = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
                } else {
                    $full_name = $user->first_name . ' ' . $user->last_name;
                }
                $employee_data += array(
                    $user->emp_id => $full_name,
                );
            }
        }
        return $employee_data;
    }

    public static function getName($user_id)
    {
        $user = User::find($user_id);
        if (!empty($user->middle_name)) {
            $full_name = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
        } else {
            $full_name = $user->first_name . ' ' . $user->last_name;
        }

        return $full_name;
    }

    public function getUserEmployee()
    {
        return User::where('active', '=', 1)->where('user_type', '=', 'employer')->get();
    }

    public function getAllActiveUser()
    {
        return User::where('active', '=', 1)->where('user_type', '!=', 'super_admin')->get();
    }

    public function getChild($parent_id)
    {
        return User::where('parent_id', '=', $parent_id)->get();
    }

    public function getOutletManger()
    {
        return User::where('active', '=', 1)->where('user_type', '=', 'branch_outlet')->get();
    }

    public function getAllMarketing()
    {
        return User::where('active', '=', 1)->where('department', '=', '0')->get();
    }

    public function getAllChildUser($multi_users)
    {
        return User::whereIn('id', $multi_users)->where('active', '=', 1)->where('department', '=', '0')->get();
    }


    public function getAllActiveUserList()
    {
        $users = User::where('active', '=', 1)->where('user_type', '!=', 'super_admin')->get();
        $user_data = array();
        foreach ($users as $user) {
            if (!empty($user->middle_name)) {
                $full_name = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
            } else {
                $full_name = $user->first_name . ' ' . $user->last_name;
            }
            $user_data += array(
                $user->id => $full_name
            );
        }
        return $user_data;
    }

    public function getUsersByFilter($filter = [], $select = ['*'])
    {
        $result = User::where($filter)->get($select);
        return $result;
    }

    public function getAdminUser()
    {
        return User::whereUserType('super_admin')->get();
    }

    public function getSuperAdminUser()
    {
        $user = User::whereUserType('super_admin')->first();
        $user_data = array();
        $user_data += array(
            $user->id => $user->full_name
        );

        return $user_data;
    }

    // Function Not Used
    public function getUserEmployeeList()
    {
        return User::where('active', '=', 1)->where('user_type', '=', 'employer')->pluck('first_name', 'id');
    }

    public function getLeadParent()
    {
        return User::where('parent_id', '=', '1')->get();
    }

    public function getEmployeeUserList()
    {
        $employer_user = User::when(true, function ($query) {
            $query->where('active', '=', 1);

            if (auth()->user()->user_type == 'employee') {
                $employee = (auth()->user()->userEmployer);
                $query->whereHas('userEmployer', function ($q) use ($employee) {
                    $q->where('organization_id', $employee->organization_id);
                });
            }
            if (auth()->user()->user_type == 'division_hr') {
                $employee = (auth()->user()->userEmployer);
                $query->whereHas('userEmployer', function ($q) use ($employee) {
                    $q->where('organization_id', $employee->organization_id);
                });
            }
        })->get();

        $employee_data = array();
        foreach ($employer_user as $key => $user) {
            if ($user->active == 1 && $user->emp_id != null) {
                if (!empty($user->middle_name)) {
                    $full_name = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
                } else {
                    $full_name = $user->first_name . ' ' . $user->last_name;
                }
                $employee_data += array(
                    $user->id => $full_name
                );
            }
        }
        return $employee_data;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = User::orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function findAllExceptOne($id)
    {
        $result = User::where('id', '!=', $id)->get();
        return $result;
    }

    public function getUserByEmpId($emp_id)
    {
        return User::where('emp_id', '=', $emp_id)->first();
    }

    public function getAdminList()
    {
        return User::select('id')->whereUserType('admin')->get();
    }

    public function getUserById($user_id)
    {
        return User::select('first_name', 'last_name')->where('id', $user_id)->first();
    }

    public function getAdmin()
    {
        return User::select('id')->whereUserType('admin')->orderBy('id', 'DESC')->limit(1)->first();
    }

    // Function Not Used
    public function getSupervisorUserList()
    {
        $users =  User::where('active', 1)
            ->whereHas('userEmployer', function ($q) {
                $q->where('is_supervisor', 1);
            })->get();

        //$employees = Employment::where('is_supervisor', '=', '1')->get();
        $user_data = array();
        foreach ($users as $key => $user) {
            $full_name = !empty($user->middle_name) ? $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name :

                $user->first_name . ' ' . $user->last_name;

            $user_data += array($user->id => $full_name);
        }
        return $user_data;
    }

    public function getAll()
    {
        $result = User::where('active', 1)->orderBy('id', 'ASC')->get();
        return $result;
    }


    // Function Not Used
    public function getAllActiveUserListExpectEmployee()
    {
        $users = User::where('active', '=', 1)->where('user_type', '!=', 'employee')->get();
        $user_data = array();
        foreach ($users as $user) {
            if (!empty($user->middle_name)) {
                $full_name = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
            } else {
                $full_name = $user->first_name . ' ' . $user->last_name;
            }
            $user_data += array(
                $user->id => $full_name
            );
        }
        return $user_data;
    }

    //Function Not Used
    public function getUserFromOrganization($organization_id)
    {
        $users = User::where('active', '=', 1)->whereHas('userEmployer', function ($query) use ($organization_id) {
            $query->where('organization_id', $organization_id);
        })->orderBy('id', 'desc')->get();

        $user_data = array();

        foreach ($users as $user) {
            if (!empty($user->middle_name)) {
                $full_name = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
            } else {
                $full_name = $user->first_name . ' ' . $user->last_name;
            }
            $user_data += array(
                $user->id => $full_name
            );
        }
        return $user_data;
    }

    public function getListExceptAdmin()
    {
        if (auth()->user()->user_type == 'division_hr') {
            $filterArray = [
                'user_type' => ['supervisor', 'division_hr','hr'],
                'organization_id' => optional(auth()->user()->userEmployer)->organization_id,
            ];
        } else {
            $filterArray = [
                'user_type' => ['supervisor', 'division_hr','hr'],
            ];
        }
        $mergeArray = array_merge(['model' => 'user'], $filterArray);
        $userLists = employee_helper()->getUserListsByType($mergeArray);
        return $userLists;
    }

    public function getEmployeeUserListByFilter($filters)
    {

        $employer_user = User::when(true, function ($query) use($filters) {
            $query->where('active', '=', 1);

            if (isset($filters['organization_id'])) {
                $query->whereHas('userEmployer', function ($q) use ($filters) {
                    $q->whereIn('organization_id', $filters['organization_id']);
                        });
                
            }
            if (isset($filters['branch_id'])) {
                $query->whereHas('userEmployer', function ($q) use ($filters) {
                    $q->whereIn('branch_id', $filters['branch_id']);
                        });
                
            }
            if (isset($filters['department_id'])) {
                $query->whereHas('userEmployer', function ($q) use ($filters) {
                    $q->whereIn('department_id', $filters['department_id']);
                        });
                
            }
           
        })->get();

        $employee_data = array();
        foreach ($employer_user as $key => $user) {
            if ($user->active == 1 && $user->emp_id != null) {
                if (!empty($user->middle_name)) {
                    $full_name = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
                } else {
                    $full_name = $user->first_name . ' ' . $user->last_name;
                }
                $employee_data += array(
                    $user->id => $full_name
                );
            }
        }
        return $employee_data;
    }

    public function storeActivityLog($data){
        return LoginLogoutLog::create($data);
    }

    public function activityLogs($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        // if ($authUser->user_type == 'division_hr') {
        //     $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        // }
        $result = LoginLogoutLog::when(array_keys($filter, true), function ($query) use ($filter, $authUser) {
            // $query->whereNotNull('employee_id');

            if (isset($filter['organization_id']) && $filter['organization_id'] != '') {
                $query->whereHas('employee', function ($q) use ($filter) {
                    $q->where('organization_id', $filter['organization_id']);
                });
            }

            if (isset($filter['employee_id']) && $filter['employee_id'] != '') {
                $query = $query->where('employee_id', $filter['employee_id']);
            }

            if (isset($filter['date_range'])) {
                $filterDates = explode(' - ', $filter['date_range']);
                $query->where('date', '>=', $filterDates[0]);
                $query->where('date', '<=', $filterDates[1]);
            }

            if (isset($filter['from_nep_date']) && !empty($filter['from_nep_date'])) {
                $query->where('nepali_date', '>=', $filter['from_nep_date']);
            }

            if (isset($filter['to_nep_date']) && !empty($filter['to_nep_date'])) {
                $query->where('nepali_date', '<=', $filter['to_nep_date']);
            }

            if (isset($filter['type']) && $filter['type'] != '') {
                $query = $query->where('type', $filter['type']);
            }

            if ($authUser->user_type == 'employee' || $authUser->user_type == 'supervisor') { //supervisor logic changes
                $query->where('employee_id', $authUser->emp_id);
            } elseif ($authUser->user_type == 'division_hr') {
                $query->whereHas('employee', function ($q) use ($authUser) {
                    $q->where('organization_id', optional($authUser->userEmployer)->organization_id);
                });
            } elseif ($authUser->user_type == 'hr' || $authUser->user_type == 'admin' || $authUser->user_type == 'super_admin') {
                // $query->where('employee_id', '!=', null);
            }
               
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));
        return $result;
    }
}
