<?php

namespace App\Modules\Shift\Http\Controllers;

use App\Traits\LogTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Repositories
use Illuminate\Routing\Controller;
use App\Modules\Shift\Entities\Shift;
use Illuminate\Support\Facades\Route;
use App\Modules\Shift\Entities\ShiftSeason;
use App\Modules\Shift\Entities\ShiftDayWise;
use App\Modules\Shift\Repositories\GroupInterface;
use App\Modules\Shift\Repositories\ShiftInterface;
use App\Modules\Shift\Repositories\ShiftGroupInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Shift\Repositories\EmployeeShiftInterface;

class ShiftController extends Controller
{

    use LogTrait;
    private $shift, $employment, $group, $employeeShift;

    public function __construct(
        ShiftInterface $shift,
        EmployeeInterface $employment,
        ShiftGroupInterface $group,
        EmployeeShiftInterface $employeeShift
    ) {
        $this->employment = $employment;
        $this->shift = $shift;
        $this->group = $group;
        $this->employeeShift = $employeeShift;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $inputData = $request->all();

        $data['title'] = 'Shift';
        $data['shiftModels'] = $this->shift->findAll(null, $inputData);
        $data['employeeList'] = $this->employment->getList();
        // dd($data);
        return view('shift::shift.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['title'] = "Shift";
        $data['titleList'] = [
            'Day' => 'Day',
            'Morning' => 'Morning',
            'Night' => 'Night',
            'Custom' => 'Custom'
        ];
        $data['isEdit'] = false;
        $data['daysOfWeek'] = ['Sun' => 'Sunday', 'Mon' => 'Monday', 'Tue' => 'Tuesday', 'Wed' => 'Wednesday', 'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday'];
        return view('shift::shift.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    // public function store(Request $request)
    // {
    //     $data = $request->except('_token');
    //     $inputData['created_by'] = auth()->user()->id;
    //     $inputData['updated_by'] = auth()->user()->id;

    //     try {
    //         $inputData['title'] = $data['title'];
    //         $inputData['custom_title'] = $data['custom_title'];

    //         $shift = $this->shift->save($inputData);
    //         if(isset($shift)){
    //             foreach ($data['day'] as $day =>$fullName) {
    //                 $dayWiseShift['shift_id'] = $shift['id'];
    //                 $dayWiseShift['day'] = $day;
    //                 $dayWiseShift['start_time'] = $data['start_time'][$day];
    //                 $dayWiseShift['end_time'] = $data['end_time'][$day];

    //                 ShiftDayWise::create($dayWiseShift);
    //             }
    //         }
    //         $logData=[
    //             'title'=>'New shift Created',
    //             'action_id'=>$shift->id,
    //             'action_model'=>get_class($shift),
    //             'route'=>route('shift.index')
    //         ];
    //         $this->setActivityLog($logData);
    //         toastr()->success('Shift Added Successfully.');
    //     } catch (\Throwable $e) {
    //         toastr()->error('Something Went Wrong !!!');
    //     }

    //     return redirect()->route('shift.index');
    // }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        // dd($data);
        $inputData['created_by'] = auth()->user()->id;
        $inputData['updated_by'] = auth()->user()->id;
        // try {
        $inputData['title'] = $data['title'];
        $inputData['custom_title'] = $data['custom_title'];
        // $inputData['seasonal'] = $data['seasonal'] ?? 1;

        $shift = $this->shift->save($inputData);
        if (isset($shift)) {
            // if($data['seasonal']==1){
            foreach ($data['date_from'] as $key => $date_from) {
                $shift_season = ShiftSeason::create([
                    'date_from' => $date_from,
                    'date_to' => $data['date_to'][$key],
                    'shift_id' => $shift->id,
                    'is_multi_day_shift' => $data['is_multi_day_shift'][$key],
                ]);
                if ($shift_season) {
                    foreach ($data['season_day'] as $day => $fullName) {
                        $dayWiseShift['shift_id'] = $shift['id'];
                        $dayWiseShift['day'] = $day;
                        $dayWiseShift['checkin_start_time'] = $data['season_checkin_start_time'][$day][$key];
                        $dayWiseShift['start_time'] = $data['season_start_time'][$day][$key];
                        $dayWiseShift['end_time'] = $data['season_end_time'][$day][$key];
                        $dayWiseShift['shift_season_id'] = $shift_season->id;
                        ShiftDayWise::create($dayWiseShift);
                    }
                }
            }
            // }else{
            //     foreach ($data['day'] as $day =>$fullName) {
            //         $dayWiseShift['shift_id'] = $shift['id'];
            //         $dayWiseShift['day'] = $day;
            //         $dayWiseShift['checkin_start_time'] = $data['checkin_start_time'][$day];
            //         $dayWiseShift['start_time'] = $data['start_time'][$day];
            //         $dayWiseShift['end_time'] = $data['end_time'][$day];

            //         ShiftDayWise::create($dayWiseShift);
            //     }
            // }


        }
        $logData = [
            'title' => 'New shift Created',
            'action_id' => $shift->id,
            'action_model' => get_class($shift),
            'route' => route('shift.index')
        ];
        $this->setActivityLog($logData);
        toastr()->success('Shift Added Successfully.');
        // } catch (\Throwable $e) {
        //     toastr()->error('Something Went Wrong !!!');
        // }

        return redirect()->route('shift.index');
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data['title'] = "Shift";
        $data['titleList'] = [
            'Day' => 'Day',
            'Morning' => 'Morning',
            'Night' => 'Night',
            'Custom' => 'Custom'
        ];
        $data['shiftModel'] = $this->shift->find($id);
        $data['isEdit'] = true;
        $data['daysOfWeek'] = ['Sun' => 'Sunday', 'Mon' => 'Monday', 'Tue' => 'Tuesday',  'Wed' => 'Wednesday', 'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday'];
        return view('shift::shift.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->except('_token');
            $inputData['title'] = $data['title'];
            // $inputData['is_multi_day_shift'] = $data['is_multi_day_shift'];
            $inputData['custom_title'] = $data['custom_title'];
            $inputData['upadated_by'] = auth()->user()->id;
            // $inputData['seasonal'] = $data['seasonal'];

            $isUpdate = $this->shift->update($id, $inputData);

            if (isset($isUpdate)) {
                // if($data['seasonal']==1){
                foreach ($this->shift->find($id)->shiftSeasons as $oldShiftSeason) {
                    $oldShiftSeason->seasonShiftDayWise()->delete();
                    $oldShiftSeason->delete();
                }
                foreach ($data['date_from'] as $key => $date_from) {

                    $shift_season = ShiftSeason::create([
                        'date_from' => $date_from,
                        'date_to' => $data['date_to'][$key],
                        'shift_id' => $id,
                        'is_multi_day_shift' => $data['is_multi_day_shift'][$key],
                    ]);
                    if ($shift_season) {
                        foreach ($data['season_day'] as $day => $fullName) {
                            $dayWiseShift['shift_id'] = $id;
                            $dayWiseShift['day'] = $day;
                            $dayWiseShift['checkin_start_time'] = $data['season_checkin_start_time'][$day][$key];
                            $dayWiseShift['start_time'] = $data['season_start_time'][$day][$key];
                            $dayWiseShift['end_time'] = $data['season_end_time'][$day][$key];
                            $dayWiseShift['shift_season_id'] = $shift_season->id;
                            ShiftDayWise::create($dayWiseShift);
                        }
                    }
                }

                // }else{
                //     $this->shift->find($id)->shiftDayWise()->delete();
                //     foreach ($data['day'] as $day =>$fullName) {
                //         $dayWiseShift['shift_id'] = $id;
                //         $dayWiseShift['day'] = $day;
                //         $dayWiseShift['checkin_start_time'] = $data['checkin_start_time'][$day];
                //         $dayWiseShift['start_time'] = $data['start_time'][$day];
                //         $dayWiseShift['end_time'] = $data['end_time'][$day];
                //         ShiftDayWise::create($dayWiseShift);
                //     }
                // }


            }
            // if($isUpdate){
            //     ShiftDayWise::where('shift_id', $id)->delete();

            //     foreach ($data['day'] as $day =>$fullName) {
            //         $dayWiseShift['shift_id'] = $id;
            //         $dayWiseShift['day'] = $day;
            //         $dayWiseShift['start_time'] = $data['start_time'][$day];
            //         $dayWiseShift['end_time'] = $data['end_time'][$day];
            //         ShiftDayWise::create($dayWiseShift);
            //     }
            // }
            $shift = $this->shift->find($id);
            $logData = [
                'title' => 'Shift updated',
                'action_id' => $id,
                'action_model' => get_class($shift),
                'route' => route('shift.index')
            ];
            $this->setActivityLog($logData);
            toastr()->success('Shift Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->route('shift.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            // $groups = $this->group->findAll('', ['shift_id' => $id]);
            // if($groups->total() > 0 ) {
            //     foreach ($groups as $key => $value) {
            //         if(!empty($value->getGroupMember)) {
            //             $this->group->deleteGroupMember($value->id);
            //             $this->employeeShift->deleteByGroup($value->id);
            //         }
            //     }

            //     $this->group->deleteByShift($id);
            // }

            $this->shift->delete($id);
            $logData = [
                'title' => 'Shift deleted',
                'action_id' => null,
                'action_model' => null,
                'route' => route('shift.index')
            ];
            $this->setActivityLog($logData);
            toastr()->success('Shift Deleted Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect()->back();
    }

    public function getSeasonForm(Request $request)
    {
        $data['numberIncr'] = $request->numberIncr;
        $data['title'] = "Shift";
        $data['titleList'] = [
            'Day' => 'Day',
            'Morning' => 'Morning',
            'Night' => 'Night',
            'Custom' => 'Custom'
        ];
        $data['isEdit'] = $request->isEdit;
        $data['shiftModel'] = $this->shift->find($request->shift_id);
        $data['daysOfWeek'] = ['Sun' => 'Sunday', 'Mon' => 'Monday', 'Tue' => 'Tuesday', 'Wed' => 'Wednesday', 'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday'];
        $view = view('shift::shift.partial.add-more', $data)->render();
        return response()->json(['result' => $view]);
    }

    public function updateDefaulShift(Request $request)
    {

        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
        ]);

        Shift::where('default', 'yes')->update(['default' => 'no']);
        $shiftGroup = Shift::find($request->shift_id);
        $shiftGroup->default = 'yes';
        $shiftGroup->save();

        return response()->json(['success' => true, 'message' => 'Default Shift updated successfully!']);
    }
}