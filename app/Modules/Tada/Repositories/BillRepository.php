<?php

namespace App\Modules\Tada\Repositories;

use App\Modules\Tada\Entities\TadaBill;

/**
 * BillRepository
 */
class BillRepository implements BillInterface
{
    public function findAll($limit = null, $filter, $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = TadaBill::orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return TadaBill::find($id);
    }

    public function update($id, $data)
    {
        return TadaBill::find($id)
            ->update($data);
    }

    public function save($data)
    {
        return TadaBill::create($data);
    }

    public function delete($id)
    {
        return TadaBill::find($id)->delete();
    }

    public function getList()
    {
        return TadaBill::pluck('title', 'id');
    }

    public function upload($file, $folder_name)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);
        $path = $this->getPath($folder_name);
        $file->move($path, $fileName);

        return $fileName;
    }

    public function getPath($folder_name)
    {
        return public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'tadabill' . DIRECTORY_SEPARATOR . $folder_name . DIRECTORY_SEPARATOR;
    }

    public function uploadBills($bills)
    {
        $filePath = [];
        foreach ($bills as $file) {
            $imageName = $file->getClientOriginalName();
            $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $imageName);
            $file->move(public_path() . TadaBill::FILE_PATH, $fileName);
            $filePath[] = $fileName;
        }

        return $filePath;
    }

    public function saveBills($images, $tada_id)
    {
        foreach ($images as $val) {
            $data = [
                'image_src' => $val,
                'tada_id' => $tada_id,

            ];
            TadaBill::create($data);
        }

    }

}
