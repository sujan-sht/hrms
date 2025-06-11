<?php

namespace App\Modules\Shift\Http\Controllers;

use App\Traits\LogTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\Shift\Entities\Shift;
use App\Modules\Shift\Entities\ShiftGroup;

use App\Modules\User\Repositories\UserInterface;
use App\Modules\Shift\Repositories\ShiftInterface;
use App\Modules\Shift\Repositories\ShiftGroupInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Shift\Repositories\EmployeeShiftInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;

class ShiftGroupController extends Controller
{
    use LogTrait;
    /**
     * @var UserInterface
     */
    private $user;
    /**
     * @var OrganizationInterface
     */
    private $organization;
    /**
     * @var EmployeeInterface
     */
    private $employee;
    /**
     * @var ShiftGroupInterface
     */
    private $shiftGroup;
    /**
     * @var ShiftInterface
     */
    protected $shift;
    /**
     * @var EmployeeShiftInterface
     */
    protected $employeeShift;

    /**
     * GroupController constructor.
     * @param UserInterface $user
     * @param OrganizationInterface $organization
     * @param ShiftGroupInterface $shiftGroup
     * @param EmployeeInterface $employee
     * @param ShiftInterface $shift
     * @param EmployeeShiftInterface $employeeShift
     */
    public function __construct(
        UserInterface $user,
        OrganizationInterface $organization,
        ShiftGroupInterface $shiftGroup,
        EmployeeInterface $employee,
        ShiftInterface $shift,
        EmployeeShiftInterface $employeeShift
    ) {

        $this->user = $user;
        $this->organization = $organization;
        $this->employee = $employee;
        $this->shiftGroup = $shiftGroup;
        $this->shift = $shift;
        $this->employeeShift = $employeeShift;
    }

    public function index()
    {
        $data['title'] = 'Shift Group';
        $data['shiftGroupModels'] = $this->shiftGroup->findAll(null, []);

        return view('shift::group.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Shift Group';
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();
        $data['shiftList'] = $this->shift->getList();

        return view('shift::group.create', $data);
    }

    // public function store(Request $request)
    // {
    //     $data = $request->all();

    //     try{
    //         $user = Auth::user();
    //         $data['created_by'] = $user->id;
    //         $shiftGroup = $this->shiftGroup->save($data);

    //         $week_days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    //         for ($i = 0; $i < sizeof($data['members']); $i++){
    //             $shiftGroupMember['group_member'] = $data['members'][$i];
    //             $shiftGroupMember['group_id'] = $shiftGroup->id;
    //             $this->shiftGroup->saveGroupMember($shiftGroupMember);

    //             foreach($week_days as $day) {
    //                 $employee_shift = [
    //                     'employee_id' => $data['members'][$i],
    //                     'shift_id' => $shiftGroup->shift_id,
    //                     'days' => $day,
    //                     'group_id' => $shiftGroup->id,
    //                     'updated_by' => $user->id,
    //                     'created_by' => $user->id
    //                 ];
    //                 $this->employeeShift->save($employee_shift);
    //             }
    //         }
    //         $logData=[
    //             'title'=>'New shift group created',
    //             'action_id'=>$shiftGroup->id,
    //             'action_model'=>get_class($shiftGroup),
    //             'route'=>route('shiftGroup.edit',$shiftGroup->id)
    //         ];
    //         $this->setActivityLog($logData);
    //         toastr()->success('Shift Group Created Successfully');
    //     } catch(\Throwable $e){
    //         toastr()->error('Something Went Wrong !!!');
    //     }
    //     return redirect(route('shiftGroup.index'));
    // }

    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $user = Auth::user();
            $data['created_by'] = $user->id;
            $week_days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            // $data['default'] = @$request->default ? 'yes' : 'no';
            $old_default = optional(ShiftGroup::where('org_id', $data['org_id'])->where('default', 'yes')->first())->update(['default' => null]);
            $data['default'] = @$request->default ? 'yes' : null;

            $otGracePeriods = isset($data['ot_grace_period']) ? (is_array($data['ot_grace_period']) ? $data['ot_grace_period'] : [$data['ot_grace_period']]) : [];
            $gracePeriodCheckouts = isset($data['grace_period_checkout']) ? (is_array($data['grace_period_checkout']) ? $data['grace_period_checkout'] : [$data['grace_period_checkout']]) : [];
            $shift_season_ids = isset($data['shift_season_id']) ? (is_array($data['shift_season_id']) ? $data['shift_season_id'] : [$data['shift_season_id']]) : [];
            $gracePeriodCheckinForPenalty = isset($data['grace_period_checkin_for_penalty']) ? (is_array($data['grace_period_checkin_for_penalty']) ? $data['grace_period_checkin_for_penalty'] : [$data['grace_period_checkin_for_penalty']]) : [];
            $gracePeriodCheckoutForPenalty = isset($data['grace_period_checkout_for_penalty']) ? (is_array($data['grace_period_checkout_for_penalty']) ? $data['grace_period_checkout_for_penalty'] : [$data['grace_period_checkout_for_penalty']]) : [];
            foreach ($otGracePeriods as $index => $otGracePeriod) {
                $data['ot_grace_period'] = $otGracePeriod;
                $data['grace_period_checkout'] = $gracePeriodCheckouts[$index] ?? null;
                $data['shift_season_id'] = $shift_season_ids[$index] ?? null;
                $data['grace_period_checkin_for_penalty'] = $gracePeriodCheckinForPenalty[$index] ?? null;
                $data['grace_period_checkout_for_penalty'] = $gracePeriodCheckoutForPenalty[$index] ?? null;
                // dd($data, $index);
                $shiftGroup = $this->shiftGroup->save($data);


                for ($i = 0; $i < sizeof($data['members']); $i++) {
                    $shiftGroupMember['group_member'] = $data['members'][$i];
                    $shiftGroupMember['group_id'] = $shiftGroup->id;
                    $this->shiftGroup->saveGroupMember($shiftGroupMember);

                    foreach ($week_days as $day) {
                        $employee_shift = [
                            'employee_id' => $data['members'][$i],
                            'shift_id' => $shiftGroup->shift_id,
                            'days' => $day,
                            'group_id' => $shiftGroup->id,
                            'updated_by' => $user->id,
                            'created_by' => $user->id
                        ];
                        $this->employeeShift->save($employee_shift);
                    }
                }
                $logData = [
                    'title' => 'New shift group created',
                    'action_id' => $shiftGroup->id,
                    'action_model' => get_class($shiftGroup),
                    'route' => route('shiftGroup.edit', $shiftGroup->id)
                ];
                $this->setActivityLog($logData);
            }


            toastr()->success('Shift Group Created Successfully');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('shiftGroup.index'));
    }

    public function edit($id)
    {
        $data['title'] = 'Shift Group';
        $data['organizationList'] = $this->organization->getList();
        $data['employeeList'] = $this->employee->getList();
        $data['shiftList'] = $this->shift->getList();
        $data['shiftGroupModel'] = $this->shiftGroup->find($id);

        return view('shift::group.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['shift_season_id'] = $data['shift_season_id'][0];
        // dd($data);
        try {
            $user = Auth::user();
            $data['update_by'] = $user->id;
            $old_default = optional(ShiftGroup::where('org_id', $data['org_id'])->where('default', 'yes')->first())->update(['default' => null]);
            $data['default'] = @$request->default ? 'yes' : null;

            $this->shiftGroup->update($id, $data);
            $this->shiftGroup->updateGroupMember($id, $data['members']);
            $shiftGroup = $this->shiftGroup->find($id);
            $week_days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

            for ($i = 0; $i < sizeof($data['members']); $i++) {
                foreach ($week_days as $day) {
                    $checkEmpShift = $this->employeeShift->findOne([
                        'employee_id' => $data['members'][$i],
                        'shift_id' => $data['shift_id'],
                        'days' => $day,
                        'group_id' => $id
                    ]);
                    if (empty($checkEmpShift)) {
                        $employee_shift = [
                            'employee_id' => $data['members'][$i],
                            'shift_id' => $data['shift_id'],
                            'days' => $day,
                            'group_id' => $id,
                            'updated_by' => $user->id,
                            'created_by' => $user->id
                        ];
                        $this->employeeShift->save($employee_shift);
                    }
                }
            }
            $logData = [
                'title' => 'Shift group updated',
                'action_id' => $shiftGroup->id,
                'action_model' => get_class($shiftGroup),
                'route' => route('shiftGroup.edit', $shiftGroup->id)
            ];
            $this->setActivityLog($logData);
            toastr()->success('Group Updated Successfully');
        } catch (\Throwable $e) {
            // dd($e);
            toastr()->error('Something Went Wrong !!!');
            return back();
        }
        return redirect(route('shiftGroup.index'));
    }

    public function destroy($id)
    {
        try {
            $shiftGroup = $this->shiftGroup->find($id);
            if (!empty($shiftGroup) && !empty($shiftGroup->groupMembers)) {
                $this->shiftGroup->deleteGroupMember($id);
                $this->employeeShift->deleteByGroup($id);
            }
            $this->shiftGroup->delete($id);
            $logData = [
                'title' => 'Shift group deleted',
                'action_id' => null,
                'action_model' => null,
                'route' => route('shiftGroup.index')
            ];
            $this->setActivityLog($logData);
            toastr()->success('Group Deleted Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('shiftGroup.index'));
    }

    public function setSeasonsalShift(Request $request)
    {
        $shiftId = $request->shift_id;
        $shift = Shift::with('shiftSeasons')->find($shiftId);
        if ($shift) {
            return view('shift::group.partial.grace-time', compact('shift'));
        }
        return response()->json(['error' => 'Shift not found'], 404);
    }

    public function updateDefaulGroup(Request $request)
    {

        $request->validate([
            'shift_group_id' => 'required|exists:shift_groups,id',
        ]);

        ShiftGroup::where('org_id', $request->org_id)->where('default', 'yes')->update(['default' => null]);
        $shiftGroup = ShiftGroup::where('org_id', $request->org_id)->find($request->shift_group_id);
        $shiftGroup->default = 'yes';
        $shiftGroup->save();

        return response()->json(['success' => true, 'message' => 'Default shift group updated successfully!']);
    }

    public function getShiftGroupsByOrg(Request $request)
    {
        $orgId = $request->input('organization_id');
        $groups = ShiftGroup::where('org_id', $orgId)->pluck('group_name', 'id');
        $selectedId = ShiftGroup::where('org_id', $orgId)->where('default', 'yes')->pluck('id');

        return response()->json([
            'data' => $groups,
            'selected_id' => $selectedId,
        ]);
    }
}
