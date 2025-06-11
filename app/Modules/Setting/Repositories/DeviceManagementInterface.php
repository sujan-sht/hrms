<?php
namespace App\Modules\Setting\Repositories;

interface DeviceManagementInterface
{
    public function findAll();

    public function find($id);

    public function save($data);

    public function update($id,$data);

    public function delete($id);

    public function findAllActiveDevice();
}
