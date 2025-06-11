<?php

namespace App\Modules\Notification\Repositories;


interface NotificationInterface
{
    public function findAll($id,$limit=null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function save($data);
    
    public function update($id,$data);

    public function delete($id);
    
    public function findAllNotification($id);
    
    public function countActiveNotification($id);
    
    
}