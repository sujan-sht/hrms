<?php
namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\DeviceManagement;
use App\Modules\Setting\Entities\Setting;

class DeviceManagementRepository implements DeviceManagementInterface
{

    public function findAll(){
        return DeviceManagement::all();

    }
    public function find($id){
        return DeviceManagement::find($id);
    }

    public function save($data){
        return DeviceManagement::create($data);
    }

    public function update($id,$data){
        $result = $this->find($id);
        return $result->update($data);
    }

    public function delete($id){
        $result = $this->find($id);
        return $result->delete();
    }

    public function findAllActiveDevice()
    {
        return DeviceManagement::select('id', 'organization_id as org_id', 'ip_address as ipaddress', 'port', 'device_id', 'communication_password')->where('status', '=', '1')->get();
    }
}
