<?php

namespace App\Modules\Document\Repositories;

use App\Modules\Document\Entities\Document;
use App\Modules\Document\Entities\DocumentEmployee;
use App\Modules\Document\Entities\DocumentOrganization;

class DocumentRepository implements DocumentInterface
{
    public function getList()
    {
        return Document::pluck('title', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Document::when(true, function ($query) use ($filter) {

            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }

            if (isset($filter['type']) && !empty($filter['type'])) {
                $query->where('type', $filter['type']);
            }


            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }

            $query->where('created_by', auth()->user()->id);

            // if (auth()->user()->user_type == 'employee') {
            //     if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
            //         $query->where('organization_id', $filter['organization_id']);
            //     }
            // }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return Document::find($id);
    }

    public function create($data)
    {
        return Document::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return Document::destroy($id);
    }

    public function getDocumentEmployeeList($documentId)
    {
        return DocumentEmployee::where('document_id', $documentId)->pluck('employee_id');
    }

    public function sharedList($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Document::when(true, function ($query) use ($filter) {

            if (isset($filter['title']) && !empty($filter['title'])) {
                $query->where('title', 'like', '%' . $filter['title'] . '%');
            }

            if (isset($filter['status']) && !empty($filter['status'])) {
                $query->where('status', $filter['status']);
            }

            $query->whereHas('documentEmployee', function ($q) use ($filter) {
                $q->where('employee_id', auth()->user()->emp_id);
            });
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findDocOrganization($document_id)
    {
        return DocumentOrganization::find($document_id);
    }
}
