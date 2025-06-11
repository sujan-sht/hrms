<?php

namespace App\Modules\User\Repositories;

use Illuminate\Support\Facades\Request;

interface UserInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);
    public function findAllExceptOne($id);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function deleteEmpUser($id);

    public function checkUsername($username);
    public function othersUsername($username, $userid);

    public function getUserByUsername($username);

    public function getUserId($emp_id);

    public function getUserEmployee();

    public function getAllActiveUser();

    public function getChild($parent_id);

    public function getEmployeeList();

    public function getOutletManger();

    public function getAllMarketing();

    public function getAllChildUser($multi_users);

    public function getAllActiveUserList();

    public function getAdminUser();

    public function getUserEmployeeList();

    public function getLeadParent();

    public function getEmployeeUserList();

    public function getUserByEmpId($emp_id);

    public function getAdminList();

    public function getUserById($user_id);

    public function getAdmin();

    public static function getName($userId);

    public function getSupervisorUserList();

    public function getAll();

    public function getAllActiveUserListExpectEmployee();

    public function getUserFromOrganization($organization_id);

    public function getListExceptAdmin();
    public function getEmployeeUserListByFilter($filters);

    public function storeActivityLog($data);

    public function activityLogs($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);
}
