<?php

namespace App\Modules\FiscalYearSetup\Repositories;

use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;

class FiscalYearSetupRepository implements FiscalYearSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = FiscalYearSetup::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['start_date']) && !empty($filter['start_date'])) {
                $query->whereDate('start_date', '>=', $filter['start_date']);
            }
            if (isset($filter['end_date']) && !empty($filter['end_date'])) {
                $query->whereDate('end_date', '<=', $filter['end_date']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return FiscalYearSetup::find($id);
    }
    public function find()
    {
        return FiscalYearSetup::pluck('fiscal_year', 'fiscal_year');
    }
    public function findEnglishFiscalYear()
    {
        return FiscalYearSetup::pluck('fiscal_year_english', 'fiscal_year_english');
    }

    public function create($data)
    {
        return FiscalYearSetup::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return FiscalYearSetup::destroy($id);
    }

    public function getCurrentFiscalYear()
    {
        return FiscalYearSetup::where('status', 1)->pluck('fiscal_year', 'id');
    }
    public function getFiscalYear()
    {
        return FiscalYearSetup::where('status', 1)->first();
    }

    public function getFiscalYearList()
    {
        return FiscalYearSetup::latest()->pluck('fiscal_year', 'id');
    }
}
