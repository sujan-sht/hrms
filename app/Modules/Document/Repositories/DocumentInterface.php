<?php

namespace App\Modules\Document\Repositories;

interface DocumentInterface
{
    public function getList();

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function getDocumentEmployeeList($documentId);

    public function sharedList($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findDocOrganization($document_id);
}
