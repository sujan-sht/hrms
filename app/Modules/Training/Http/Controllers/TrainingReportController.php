<?php

namespace App\Modules\Training\Http\Controllers;

use App\Helpers\DateTimeHelper;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\FiscalYearSetup\Repositories\FiscalYearSetupInterface;
use App\Modules\Training\Repositories\TrainingInterface;
use App\Modules\Training\Repositories\TrainingParticipantInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TrainingReportController extends Controller
{
    protected $training;
    protected $trainingParticipant;
    protected $trainingAttendance;
    protected $dropdown;
    protected $fiscalYear;

    public function __construct(
        TrainingInterface $training,
        DropdownInterface $dropdown,
        TrainingParticipantInterface $trainingParticipant,
        FiscalYearSetupInterface $fiscalYear
    ) {
        $this->training = $training;
        $this->trainingParticipant = $trainingParticipant;
        $this->dropdown = $dropdown;
        $this->fiscalYear = $fiscalYear;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function annualCalendarReport()
    {

        $data['monthLists']=(new DateConverter())->getNepMonths();
        $data['trainings'] = $this->training->no_of_mandays_month_and_division_wise();
        // dd($data['trainings']->toArray());
        return view('training::training-report.annual-training-calendar', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('training::create');
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
        return view('training::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('training::edit');
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
