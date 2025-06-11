<?php
namespace App\Modules\Payroll\Repositories;

use App\Modules\Payroll\Entities\DeductionSetup;
use App\Modules\Payroll\Entities\DeductionSetupReferenceSalaryType;

class DeductionSetupRepository implements DeductionSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'ASC'], $status = [0, 1])
    {
        $authUser = auth()->user();
        if($authUser->user_type == 'division_hr') {
            $filter['organizationId'] = optional($authUser->userEmployer)->organization_id;
        }
        $result = DeductionSetup::query();
        if(isset($filter['organizationId'])) {
            $result->where('organization_id', $filter['organizationId']);
        }
        // if(auth()->user()->user_type != 'admin' && auth()->user()->user_type != 'super_admin')
        // {
        //     $result->where('created_by',auth()->user()->id);
        // }

        $result = $result->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return DeductionSetup::find($id);
    }

    public function getList($params=[])
    {
        if(isset($params['organizationId'])) {
            $result = DeductionSetup::where('organization_id', $params['organizationId'])->pluck('title', 'id');
        } else {
            $result = DeductionSetup::pluck('title', 'id');
        }

        return $result;
    }
    public function getFixedList(){
        $result = DeductionSetup::where('method',1)->pluck('title', 'id');
        return $result;
    }
    public function getMonthlyDeductionList()
    {
        $result = DeductionSetup::where('monthly_deduction',11)->pluck('title', 'id');
        return $result;

    }

    public function save($data)
    {
        return DeductionSetup::create($data);
    }
    public function saveDetail($data)
    {
        return DeductionSetupReferenceSalaryType::create($data);
    }

    public function update($id, $data)
    {
        $result = DeductionSetup::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return DeductionSetup::destroy($id);
    }
    public function deleteChild($id)
    {
        $result = DeductionSetupReferenceSalaryType::where('deduction_setup_id', $id)->delete();
        return $result;
    }

}
