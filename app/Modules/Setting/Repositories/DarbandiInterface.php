<?php

namespace App\Modules\Setting\Repositories;

interface DarbandiInterface
{
    public function findAll();

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);
}
