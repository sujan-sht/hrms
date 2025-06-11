<?php
namespace App\Modules\Setting\Repositories;

interface FestivalAllowanceSetupInterface
{
    public function getdata();

    public function find($id);

    public function save($data);

    public function update($id,$data);

}
