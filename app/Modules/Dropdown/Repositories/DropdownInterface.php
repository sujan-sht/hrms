<?php

namespace App\Modules\Dropdown\Repositories;

interface DropdownInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function getList();

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function countTotal();

    public function getFieldBySlug($slug);
    // getFieldBySlug

    public function getFieldIdFromSlug($slug);

    public function getDropdownById($id);

    public function getUserType($slug);

    public function getAllFieldsBySlug($slug);

    public function findByTitle($branch, $data);

    public function countFieldBySlug($slug);

    public function getByDropvalue($str);
}
