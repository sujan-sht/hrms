<?php
namespace App\Modules\User\Repositories;


use App\Modules\User\Entities\UserRole;

class UserRoleRepository implements UserRoleInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = UserRole::orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id){
        return UserRole::find($id);
    }

    public function save($data){
        return UserRole::create($data);
    }

    public function update($id,$data){
        $result = UserRole::where('user_id', '=', $id);
        return $result->update($data);
    }

    public function delete($id){
        return UserRole::where('user_id', '=', $id)->delete($id);
    }

    public function getByUserId($id){
        $result = UserRole::where('user_id', '=', $id)->get();
        return $result;
    }

    public function getRoleById($id){
        $result = UserRole::where('user_id', '=', $id)->first();
        return $result;
    }

}
