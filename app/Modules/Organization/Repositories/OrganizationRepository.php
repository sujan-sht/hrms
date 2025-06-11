<?php

namespace App\Modules\Organization\Repositories;

use App\Modules\Organization\Entities\Organization;

class OrganizationRepository implements OrganizationInterface
{
    public function getList($filter = [])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr' || $authUser->user_type == 'employee') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $models = Organization::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['except_id']) && !empty($filter['except_id'])) {
                $query->where('id', '!=', $filter['except_id']);
            }
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('id', $filter['organization_id']);
            }
        })->pluck('name', 'id');

        return $models;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr' || $authUser->user_type == 'supervisor' || $authUser->user_type == 'employee') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $result = Organization::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['name']) && !empty($filter['name'])) {
                $query->where('name', 'like', '%' . $filter['name'] . '%');
            }
            if (isset($filter['phone']) && !empty($filter['phone'])) {
                $query->where('contact', $filter['phone']);
            }
            if (isset($filter['email']) && !empty($filter['email'])) {
                $query->where('email', $filter['email']);
            }
            if (isset($filter['address']) && !empty($filter['address'])) {
                $query->where('address', 'like', '%' . $filter['address'] . '%');
            }
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('id', $filter['organization_id']);
            }
        })
            ->withCount(['employees'])
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return Organization::find($id);
    }

    public function create($data)
    {
        return Organization::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return Organization::destroy($id);
    }

    public function upload($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        $file->move(public_path() . '/' . Organization::IMAGE_PATH, $fileName);

        return $fileName;
    }

    public function uploadLetterhead($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        $file->move(public_path() . '/' . Organization::LETTER_HEAD_PATH, $fileName);

        return $fileName;
    }

    public function getAll(){
        return Organization::pluck('name','id');
    }

    public function findFirstOrganizationId()
    {
        $firstOrg = Organization::first();
        $orgId = null;
        if($firstOrg){
            $orgId =  $firstOrg->id;
        }
        return $orgId;
    }
}
