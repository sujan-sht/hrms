<?php

namespace App\Modules\FuelConsumption\Repositories;

use App\Modules\FuelConsumption\Entities\FuelConsumption;

class FuelConsumptionRepository implements FuelConsumptionInterface
{

    public function findAllByEmployee($emp_id, $limit = 10, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = ['pending', 'verified', 'approved'])
    {
        $result = FuelConsumption::when(array_keys($filter, true), function ($query) use ($filter) {

            if (isset($filter['search_from_to'])) {

                $dateRange = explode(' - ', $filter['search_from_to']);
                // dd($dateRange);

                $query->where('fuel_consump_created_date', '>=', $dateRange[0]);
                $query->where('fuel_consump_created_date', '<=', $dateRange[1]);
            }


            if (isset($filter['employee_id']) && !is_null($filter['employee_id'])) {

                $query->whereIn('emp_id', $filter['employee_id']);
            }

            if (isset($filter['status']) && !is_null($filter['status'])) {
                $query->where('status', $filter['status']);
            }
        })->where('emp_id', '=', $emp_id)->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }


    public function findAll($limit = 10, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = ['pending', 'verified', 'approved'])
    {
        $result = FuelConsumption::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['search_from_to'])) {

                $dateRange = explode(' - ', $filter['search_from_to']);
                // dd($dateRange);

                $query->where('fuel_consump_created_date', '>=', $dateRange[0]);
                $query->where('fuel_consump_created_date', '<=', $dateRange[1]);
            }
            if (isset($filter['organizationId']) && !is_null($filter['organizationId'])) {
                $query->whereHas('employeeInfo', function ($employeeQuery) use ($filter) {
                    $employeeQuery->whereIn('organization_id', $filter['organizationId']);
                });
            }
            if (isset($filter['employee_id']) && !is_null($filter['employee_id'])) {

                $query->whereIn('emp_id', $filter['employee_id']);
            }

            if (isset($filter['status']) && !is_null($filter['status'])) {
                $query->where('status', $filter['status']);
            }
        })->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return FuelConsumption::find($id);
    }

    public function save($data)
    {
        return FuelConsumption::create($data);
    }


    public function update($id, $data)
    {
        $result = FuelConsumption::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return FuelConsumption::destroy($id);
    }
}
