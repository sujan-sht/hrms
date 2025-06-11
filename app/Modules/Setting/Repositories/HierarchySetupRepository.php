<?php

namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\HierarchySetup;
use App\Modules\Setting\Entities\OrganizationDepartment;
use App\Modules\Setting\Entities\OrganizationDesignation;
use App\Modules\Setting\Entities\OrganizationLevel;
use App\Modules\Setting\Entities\Setting;

class HierarchySetupRepository implements HierarchySetupInterface
{
    public function getList()
    {
        return HierarchySetup::pluck('organization_id', 'id');
    }

    public function departmentList()
    {
        return OrganizationDepartment::pluck('department_name', 'id');
    }

    public function levelList()
    {
        return OrganizationLevel::pluck('level_name', 'id');
    }

    public function designationList()
    {
        return OrganizationDesignation::pluck('designation_name', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = HierarchySetup::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
                $query->where('organization_id', $filter['organization_id']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function find($id)
    {
        return HierarchySetup::find($id);
    }

    public function save($data)
    {
        $model = HierarchySetup::create($data);
        if ($model) {
            // <i class="icon-plus2"></i> Add data in organization department
            foreach (array_unique(array_map('trim', $data['department_name'])) as $department_name) {
                if (isset($department_name) && $department_name != '') {
                    $orgDepartmentModel = new OrganizationDepartment();
                    $orgDepartmentModel->hierarchy_setup_id = $model->id;
                    $orgDepartmentModel->department_name = $department_name;
                    $orgDepartmentModel->save();
                }
            }


            // <i class="icon-plus2"></i> Add data in organization level
            foreach (array_unique(array_map('trim', $data['level_name'])) as $level_name) {
                if (isset($level_name) && $level_name != '') {
                    $orgLevelModel = new OrganizationLevel();
                    $orgLevelModel->hierarchy_setup_id = $model->id;
                    $orgLevelModel->level_name = $level_name;
                    $orgLevelModel->save();
                }
            }

            // <i class="icon-plus2"></i> Add data in organization designation
            foreach (array_unique(array_map('trim', $data['designation_name'])) as $designation_name) {
                if (isset($designation_name) && $designation_name != '') {
                    $orgDesignationModel = new OrganizationDesignation();
                    $orgDesignationModel->hierarchy_setup_id = $model->id;
                    $orgDesignationModel->designation_name = $designation_name;
                    $orgDesignationModel->save();
                }
            }
        }

        return $model;
    }

    public function update($id, $data)
    {
        $result = $this->find($id);
        // $flag = $result->update($data);
        // if ($flag) {

        if ($result) {

            // organization department
            OrganizationDepartment::where('hierarchy_setup_id', $result->id)->delete();   // delete old records
            if (isset($data['department_name']) && !empty($data['department_name'])) {
                // <i class="icon-plus2"></i> Add data
                foreach (array_unique(array_map('trim', $data['department_name'])) as $department_name) {
                    if (isset($department_name) && $department_name != '') {
                        $orgDepartmentModel = new OrganizationDepartment();
                        $orgDepartmentModel->hierarchy_setup_id = $result->id;
                        $orgDepartmentModel->department_name = $department_name;
                        $orgDepartmentModel->save();
                    }
                }
            }
            //

            // organization level
            OrganizationLevel::where('hierarchy_setup_id', $result->id)->delete();   // delete old records
            if (isset($data['level_name']) && !empty($data['level_name'])) {
                // <i class="icon-plus2"></i> Add data
                foreach (array_unique(array_map('trim', $data['level_name'])) as $level_name) {
                    if (isset($level_name) && $level_name != '') {
                        $orgLevelModel = new OrganizationLevel();
                        $orgLevelModel->hierarchy_setup_id = $result->id;
                        $orgLevelModel->level_name = $level_name;
                        $orgLevelModel->save();
                    }
                }
                //
            }

            // organization designation
            OrganizationDesignation::where('hierarchy_setup_id', $result->id)->delete();   // delete old records
            if (isset($data['designation_name']) && !empty($data['designation_name'])) {
                // <i class="icon-plus2"></i> Add data
                foreach (array_unique(array_map('trim', $data['designation_name'])) as $designation_name) {
                    if (isset($designation_name) && $designation_name != '') {
                        $orgDesignationModel = new OrganizationDesignation();
                        $orgDesignationModel->hierarchy_setup_id = $result->id;
                        $orgDesignationModel->designation_name = $designation_name;
                        $orgDesignationModel->save();
                    }
                }
                //
            }
        }
    }

    public function delete($id)
    {
        $oldModelId = $id;

        $flag = HierarchySetup::destroy($id);
        if ($flag) {
            // delete old records
            OrganizationDepartment::where('hierarchy_setup_id', $oldModelId)->delete();
            OrganizationDesignation::where('hierarchy_setup_id', $oldModelId)->delete();
            OrganizationLevel::where('hierarchy_setup_id', $oldModelId)->delete();
        }

        return $flag;
    }
}
