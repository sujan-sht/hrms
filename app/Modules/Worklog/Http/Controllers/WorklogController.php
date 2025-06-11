<?php

namespace App\Modules\Worklog\Http\Controllers;

use App\Exports\WorklogReport;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Worklog\Entities\WorklogDetail;
use App\Modules\Worklog\Http\Requests\WorklogRequest;
use App\Modules\Worklog\Repositories\WorklogInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class WorklogController extends Controller
{
    protected $worklog;
    protected $employees;
    protected $dropdownObj;

    public function __construct(WorklogInterface $worklog, EmployeeInterface $employees, DropdownInterface $dropdownObj)
    {
        $this->worklog = $worklog;
        $this->employees = $employees;
        $this->dropdownObj = $dropdownObj;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $data['worklogs'] = $this->worklog->findAll(50, $filter);
        $data['employees'] = $this->employees->getList();
        $data['projects'] = $this->dropdownObj->getFieldBySlug('project');
        $data['statusList'] = $this->worklog->getStatus();
        return view('worklog::worklog.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['employees'] = $this->employees->getList();
        $data['statusList'] = $this->worklog->getStatus();
        $data['projects'] = $this->dropdownObj->getFieldBySlug('project');
        return view('worklog::worklog.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->except(['_token']);
        $inputData['date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['date']) : $inputData['date'];

        try {
            $worklog = $this->worklog->save($inputData);

            foreach ($inputData['multi'] as $key => $value) {
                if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') {
                    $value['employee_id'] = auth()->user()->emp_id;
                }
                $workLogDetail = new WorklogDetail($value);
                $worklog->workLogDetail()->save($workLogDetail);
            }
            toastr()->success('Work log Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect(route('worklog.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['worklog'] = $this->worklog->find($id);
        return view('worklog::worklog.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        // $worklogDetail = $data['worklogDetail'] = WorklogDetail::findOrFail($id);
        $data['employees'] = $this->employees->getList();
        $data['statusList'] = $this->worklog->getStatus();
        $data['worklog'] = $this->worklog->find($id);
        $data['projects'] = $this->dropdownObj->getFieldBySlug('project');
        return view('worklog::worklog.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {

        $inputData = $request->except(['_token']);
        $inputData['date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['date']) : $inputData['date'];

        try {
            $worklog = $this->worklog->find($id);
            $worklog->update($inputData);

            $worklog->workLogDetail()->delete();
            foreach ($inputData['multi'] as $key => $value) {
                if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor') {
                    $value['employee_id'] = auth()->user()->emp_id;
                }

                $workLogDetail = new WorklogDetail($value);
                $worklog->workLogDetail()->save($workLogDetail);
            }
            toastr()->success('Work log Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect(route('worklog.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $worklog = $this->worklog->find($id);
            $worklog->workLogDetail()->delete();
            $worklog->delete();
            toastr('Work log Deleted Successfully', 'success');
            return redirect()->route('worklog.index');
        } catch (Exception $e) {
            toastr('Error While Deleting Work log', 'error');
            return redirect()->route('worklog.index');
        }
    }

    /**
     * Update the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function updateStatus(Request $request)
    {
        try {
            $data = $request->only(['id', 'status']);
            $this->worklog->update($data['id'], $request->only(['status']));
            toastr('Work log Updated Successfully', 'success');
            return redirect()->route('worklog.index');
        } catch (Exception $e) {
            toastr('Error While Updating Work log', 'error');
            return redirect()->route('worklog.index');
        }
    }

    public function exportWorklogReport(Request $request)
    {
        try {
            $filter = $request->all();
            $worklogs = $this->worklog->findAll(null, $filter);
            return Excel::download(new WorklogReport($worklogs), 'worklog-report.xlsx');
        } catch (Exception $e) {
            dd($e->getMessage());
            toastr('Error While Exporting Worklog', 'error');
        }
        return back();
    }
}
