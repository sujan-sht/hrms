<?php

namespace App\Modules\User\Repositories;


interface RoleInterface
{
    public function findAll($limit=null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function save($data);
    
    public function getList();
    
    public function savePermission($data);
    
    public function findPermissionById($id);
    
    public function deletePermission($id);

    public function update($id,$data);

    public function delete($id);
    
    public function findByTitle($data);
}