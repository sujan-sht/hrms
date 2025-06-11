<?php

namespace App\Modules\Api\Http\Controllers;

use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\Holiday\Entities\Holiday;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class HolidayController extends ApiController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $currentFiscalyear = FiscalYearSetup::currentFiscalYear();
            $holidays = Holiday::when(true, function ($query) use ($currentFiscalyear) {
                $query->where('status', '=', 11);
                $query->where('fiscal_year_id', getCurrentFiscalYearId());

                if (auth()->user()->user_type == 'employee') {
                    $activeEmployeeModel = optional(auth()->user())->userEmployer;
                    $query->GetEmployeeWiseHoliday($activeEmployeeModel, true, true);
                }

                if (auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'division_hr') {
                    $activeEmployeeModel = optional(auth()->user())->userEmployer;
                    $query->GetEmployeeWiseHoliday($activeEmployeeModel);
                }

                $query->whereHas('holidayDetail', function ($query)  use ($currentFiscalyear) {
                    $query->whereDate('eng_date', '>=', $currentFiscalyear->start_date_english);
                    $query->whereDate('eng_date', '<=', $currentFiscalyear->end_date_english);
                });
            })->get();

            $returnArray = [];
            foreach ($holidays as $holiday) {
                foreach ($holiday->holidayDetail as $value) {
                    $nep_date_arr = explode('-', $value['nep_date']);
                    $nep_month = date_converter()->_get_nepali_month($nep_date_arr[1]);

                    $returnArray[] = [
                        'id' => $value['id'],
                        'title' => $value['sub_title'],
                        'date' => date('D,M d,Y', strtotime($value['eng_date'])),
                        'nepali_date' => $nep_month.' '.$nep_date_arr[2],
                        'type' => 'holiday',
                        'month' => $nep_month,
                        'nepMonth' => $nep_date_arr[1]
                    ];
                }
            }

            $collection = collect($returnArray);
            $sorted = $collection->sortBy('nepMonth');
            $grouped = $sorted->groupBy('month');

            $result = [];
            foreach ($grouped as $month => $detailArray) {
                $result[] = [
                    'month' => $month,
                    'detail' => $detailArray
                ];
            }

            $resultData = [
                'current_fiscal_year' => $currentFiscalyear->fiscal_year,
                'holidayDetail' => $result
            ];

            return $this->respondSuccess($resultData);
            
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('api::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('api::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('api::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
