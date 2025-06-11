<?php

namespace App\Modules\Training\Http\Controllers;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Training\Entities\TrainingAttendance;
use App\Modules\Training\Entities\TrainingParticipant;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

use App\Modules\Training\Http\Requests\CreateTrainingAttendanceRequest;
use App\Modules\Training\Repositories\TrainingAttendanceInterface;
use App\Modules\Training\Repositories\TrainingInterface;
use App\Modules\Training\Repositories\TrainingParticipantInterface;
use Illuminate\Support\Facades\Redirect;

class TrainingAttendanceController extends Controller
{
    protected $trainingAttendance;
    protected $trainingParticipant;
    protected $employee;
    protected $training;

    public function __construct(TrainingAttendanceInterface $trainingAttendance, TrainingParticipantInterface $trainingParticipant, EmployeeInterface $employee, TrainingInterface $training)
    {
        $this->trainingAttendance = $trainingAttendance;
        $this->trainingParticipant = $trainingParticipant;
        $this->employee = $employee;
        $this->training = $training;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, $training_id)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['trainingAttendanceModels'] = $this->trainingAttendance->findAll($training_id, 20, $filter, $sort);
        $data['employeeList'] = $this->employee->getListWithEmpCode();
        $data['trainingModel'] = $this->training->findOne($training_id);

        return view('training::training-attendance.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($training_id)
    {
        $data['isEdit'] = false;
        // $data['paticipantList'] = $this->trainingParticipant->getList($training_id);
        $data['trainingModel'] = $this->training->findOne($training_id);
        $data['paticipantList'] = TrainingAttendance::where('training_id', $training_id)->pluck('employee_id')->toArray();
        $data['employeeList'] = $this->employee->getListWithEmpCode();
        foreach ($data['employeeList'] as $empKey => $emp) {
            if (in_array($empKey, $data['paticipantList'])) {
                unset($data['employeeList'][$empKey]);
            }
        }

        return view('training::training-attendance.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $training_id)
    {
        // $inputData = $request->all();
        // $inputData['training_id'] = $training_id;
        // $inputData['date'] = date('Y-m-d');
        // $inputData['created_by'] = Auth::user()->id;
        // if ($inputData['is_participant'] == 1) {
        //     $inputData['employee_id'] = $inputData['participant_id'];
        // }
        // try {
        //     $this->trainingAttendance->create($inputData);
        //     toastr()->success('Training Attendee Created Successfully');
        // } catch (\Throwable $e) {
        //     toastr()->error('Something Went Wrong !!!');
        // }
        // return redirect(route('training-attendance.index', $training_id));

        $inputData = $request->all();

        try {
            if (isset($inputData['employees'])) {
                foreach ($inputData['employees'] as $key => $emp) {
                    $empModel = $this->employee->find($emp);

                    $inputData['employee_id'] = $emp;
                    $inputData['email'] = $empModel->official_email;
                    $inputData['contact_no'] = $empModel->mobile;
                    $inputData['training_id'] = $training_id;
                    $inputData['date'] = date('Y-m-d');
                    // $inputData['created_by'] = Auth::user()->id;
                    $trainingAttendee = $this->trainingAttendance->create($inputData);
                    // if ($trainingAttendee) {
                    //     $this->trainingParticipant->create($inputData);
                    // }
                }
                toastr()->success('Training Participant Created Successfully');
            } else {
                toastr()->error('Attendee is required !!!');
                return redirect()->back();
            }
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('training-attendance.index', $training_id));
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
    public function edit($training_id, $id)
    {
        $data['isEdit'] = true;
        $data['trainingAttendanceModel'] = $this->trainingAttendance->findOne($id);
        $data['employeeList'] = $this->employee->getListWithEmpCode();
        $data['trainingModel'] = $this->training->findOne($training_id);
        return view('training::training-attendance.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CreateTrainingAttendanceRequest $request, $training_id, $id)
    {
        $data = $request->all();
        $data['updated_by'] = Auth::user()->id;
        try {
            $this->trainingAttendance->update($id, $data);

            toastr()->success('Training Attendee Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('training-attendance.index', $training_id));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($training_id, $id)
    {
        try {
            $this->trainingAttendance->delete($id);

            toastr()->success('Training Attendee Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('training-attendance.index', $training_id));
    }

    //Fetch Participant Data
    public function ParticipantData(Request $request)
    {
        if ($request->ajax()) {
            $participant_id = $request->participant_id;
            if (isset($participant_id)) {
                $participant_details = $this->trainingParticipant->findByEmpId($participant_id);
            }
            return json_encode($participant_details);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->all();
        try {
            $traiingId = $data['id'];
            $this->trainingAttendance->update($traiingId, $data);
            return Redirect::back();
            toastr()->success('Training Attendance Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('training-attendance.index', $id));
    }
}
