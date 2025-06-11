<?php

namespace App\Modules\Training\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Training\Entities\TrainingParticipant;
use App\Modules\Training\Repositories\TrainingParticipantInterface;
use App\Modules\Training\Http\Requests\CreateTrainingParticipantRequest;
use App\Modules\Training\Repositories\TrainingAttendanceInterface;

class TrainingParticipantController extends Controller
{
    protected $trainingParticipant;
    protected $dropdown;
    protected $employee;
    protected $trainingAttendance;


    public function __construct(
        TrainingParticipantInterface $trainingParticipant,
        TrainingAttendanceInterface $trainingAttendance,
        DropdownInterface $dropdown,
        EmployeeInterface $employee
    ) {
        $this->trainingAttendance = $trainingAttendance;
        $this->trainingParticipant = $trainingParticipant;
        $this->dropdown = $dropdown;
        $this->employee = $employee;
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
        $data['trainingParticipantModels'] = $this->trainingParticipant->findAll($training_id, 20, $filter, $sort);
        $data['employeeList'] = $this->employee->getListWithEmpCode();
        $data['training_id'] = $training_id;
        return view('training::training-participant.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($training_id)
    {
        $data['isEdit'] = false;
        $data['training_id'] = $training_id;
        $data['trainingParticipantModels'] = TrainingParticipant::where('training_id', $training_id)->pluck('employee_id')->toArray();
        $data['employeeList'] = $this->employee->getListWithEmpCode();

        foreach ($data['employeeList'] as $empKey => $emp) {
            if (in_array($empKey, $data['trainingParticipantModels'])) {
                unset($data['employeeList'][$empKey]);
            }
        }
        return view('training::training-participant.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    //
    public function store(CreateTrainingParticipantRequest $request, $training_id)
    {
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
                    $inputData['created_by'] = Auth::user()->id;
                    $participant =  $this->trainingParticipant->create($inputData);

                    $attendee = $this->trainingAttendance->getAttendeeByFilter([
                        'employee_id' => $emp,
                        'training_id' => $training_id
                    ])->exists();

                    if ($attendee == false) {
                        $this->trainingAttendance->create($inputData);
                    }
                }
                toastr()->success('Training Participant Created Successfully');
            } else {
                toastr()->error('Employee is required !!!');
                return redirect()->back();
            }
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('training-participant.index', $training_id));
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
        $data['trainingParticipantModel'] = $this->trainingParticipant->findOne($id);
        $data['employeeList'] = $this->employee->getListWithEmpCode();
        $data['training_id'] = $training_id;
        return view('training::training-participant.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CreateTrainingParticipantRequest $request, $training_id, $id)
    {
        $data = $request->all();
        $data['updated_by'] = Auth::user()->id;
        try {
            $this->trainingParticipant->update($id, $data);

            toastr()->success('Training Participant Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('training-participant.index', $training_id));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($training_id, $id)
    {
        try {
            $this->trainingParticipant->delete($id);

            toastr()->success('Training Participant Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('training-participant.index', $training_id));
    }
}
