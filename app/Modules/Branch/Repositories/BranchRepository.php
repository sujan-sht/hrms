<?php

namespace App\Modules\Branch\Repositories;

use App\Modules\Branch\Entities\Branch;

class BranchRepository implements BranchInterface
{
    public function getList()
    {
        $filter = [];

        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $result = Branch::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
            
            if (isset($filter['id']) && !is_null($filter['id'])) {
                $id = array_filter($filter['id']);
                if(count($id)){
                    $query->where('provinces_districts_id', $filter['id']);
                }
            }

            if (isset($filter['district_id']) && !empty($filter['district_id'])) {
                $districtId = array_filter($filter['district_id']);
                if(count($districtId)){
                    $query->whereIn('district_id', $districtId);
                }
            }
        })->pluck('name', 'id');

        return $result;
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $filter['organization_id'] = optional($authUser->userEmployer)->organization_id;
        }

        $result = Branch::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }

            // if (isset($filter['name']) && !empty($filter['name'])) {
            //     $query->where('name', 'like', '%' . $filter['name'] . '%');
            // }
            // if (isset($filter['location']) && !empty($filter['location'])) {
            //     $query->where('location', 'like', '%' . $filter['location'] . '%');
            // }
            // if (isset($filter['contact']) && !empty($filter['contact'])) {
            //     $query->where('contact', $filter['contact']);
            // }
            // if (isset($filter['email']) && !empty($filter['email'])) {
            //     $query->where('email', $filter['email']);
            // }

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
        return Branch::find($id);
    }

    public function create($data)
    {
        return Branch::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return Branch::destroy($id);
    }

    public function upload($file)
    {
        // $imageName = $file->getClientOriginalName();
        // $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        // $file->move(public_path() . '/' . Branch::IMAGE_PATH, $fileName);

        // return $fileName;
    }

    public function branchListOrganizationwise($organizationId)
    {
        $models = Branch::when(true, function ($query) use ($organizationId) {
            if (isset($organizationId)) {
                $query->where('organization_id', $organizationId);
            }
        })->pluck('name', 'id');

        return $models;
    }
    public function branchListMultipleOrganizationwise($organizationId)
    {
        $models = Branch::whereIn('organization_id', $organizationId)->get()->pluck('name', 'id');
        return $models;
    }

    public function branchesData(){
        return Branch::get();
    }
}
