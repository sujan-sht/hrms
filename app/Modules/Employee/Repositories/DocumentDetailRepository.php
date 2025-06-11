<?php

namespace App\Modules\Employee\Repositories;

use App\Modules\Employee\Entities\DocumentDetail;


class DocumentDetailRepository implements DocumentDetailInterface
{
    protected $model;

    public function __construct(DocumentDetail $documentDetail)
    {
        $this->model = $documentDetail;
    }

    public function findAll($empId)
    {
        return $this->model->where('employee_id', $empId)->latest()->get();
    }

    public function findOne($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function save($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $documentDetail = $this->findOne($id);
        $documentDetail->fill($data);
        $documentDetail->update();

        return $documentDetail;
    }

    public function delete($id)
    {
        $this->findOne($id)->delete();
        return true;
    }

    public function uploadDocumentFile($file)
    {
        $name = $file->getClientOriginalName();
        $fileName = date('Y-m-d-h-i-s') . '-' . preg_replace('[ ]', '-', $name);
        $file->move(public_path() . DocumentDetail::Document_PATH, $fileName);
        return $fileName;
    }
}
