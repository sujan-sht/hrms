<?php

namespace App\Modules\Dropdown\Repositories;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Dropdown\Entities\Field;

class DropdownRepository implements DropdownInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = Dropdown::orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return Dropdown::find($id);
    }

    public function getList()
    {
        $result = Dropdown::pluck('dropvalue', 'id');
        return $result;
    }

    public function save($data)
    {
        return Dropdown::create($data);
    }

    public function update($id, $data)
    {
        $result = Dropdown::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return Dropdown::destroy($id);
    }

    public function countTotal()
    {
        return Dropdown::count();
    }

    public function getFieldBySlug($slug)
    {
        $field = Field::where('slug', '=', $slug)->first();
        if ($field) {
            return $field->dropdownValue->pluck('dropvalue', 'id');
        }
     
        return [];
    }

    public function getFieldIdFromSlug($slug)
    {
        return Field::where('slug', '=', $slug)->first()->dropdownValue;
    }

    public function getDropdownById($id)
    {
        return Dropdown::where('id', '=', $id)->first();
    }

    public function getUserType($slug)
    {
        return Field::where('slug', '=', $slug)->first()->dropdownValue->pluck('dropvalue', 'dropvalue');
    }

    public function getAllFieldsBySlug($slug)
    {
        return Field::where('slug', '=', $slug)->first()->dropdownValue;
    }

    public function findByTitle($branch, $data)
    {
        return Dropdown::where('dropvalue', '=', $data)->where('fid', '=', $branch)->get();
    }

    public function countFieldBySlug($slug)
    {
        return field::where('slug', '=', $slug)->first()->dropdownValue->count();
    }

    public function getByDropvalue($str)
    {
        return Dropdown::select('id')->where('dropvalue', $str)->first();
    }

    public function getModel($fid,$dropvalue){
       return Dropdown::where('fid',$fid)->where('dropvalue',$dropvalue)->first();
    }
}
