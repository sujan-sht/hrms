<?php 
namespace App\Modules\User\Repositories;


use App\Modules\User\Entities\Role;
use App\Modules\User\Entities\Permission;

class RoleRepository implements RoleInterface
{
    
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = Role::orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }
    
    public function find($id){
        return Role::find($id);
    }
    
    
    public function save($data){
        return Role::create($data);
    }
    
    public function getList(){
        $result = Role::pluck('name', 'id');
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
        //    $result = $result->whereIn('user_type',['employee','supervisior']);
           $result = Role::wherein('user_type',['employee','supervisor'])->pluck('name', 'id');
        }
        return $result;
    }

    
    public function savePermission($data){
        return Permission::create($data);
    }
    
    public function findPermissionById($id){
        return Permission::select('route_name')->where('role_id','=',$id)->get();
    }
    
    public function deletePermission($id){
        return Permission::where('role_id','=',$id)->delete($id);
    }
    
    public function update($id,$data){
        $result = Role::find($id);
        return $result->update($data);
    }
    
    public function delete($id){
        return Role::destroy($id);
    }

    public function findByTitle($data) {
        return Role::where('name','=',$data)->get();
    }
    
}