<?php

namespace App\Modules\Event\Http\Controllers;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\District;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\Province;
use App\Modules\Event\Http\Requests\EventRequest;
use App\Modules\Event\Repositories\EventInterface;
use App\Modules\Notification\Repositories\NotificationInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\User\Repositories\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EventController extends Controller
{
    protected $event;
    /**
     * @var UserInterface
     */
    protected $user;
    /**
     * @var NotificationInterface
     */
    protected $notification;
    protected $branch;


    protected $organization_list;
    protected $dropdown;
    protected $department;

    public function __construct(EventInterface $event, UserInterface $user, NotificationInterface $notification, OrganizationInterface $organization_list, DropdownInterface $dropdown, BranchInterface $branch, DepartmentInterface $department)
    {
        $this->event = $event;
        $this->user = $user;
        $this->notification = $notification;
        $this->organization_list = $organization_list;
        $this->dropdown = $dropdown;
        $this->branch = $branch;
        $this->department = $department;
    }

    public function index(Request $request)
    {
        $search = $request->all();
        $search['creator'] = 'admin';

        $data['event'] = $this->event->findAll(20, $search);
        return view('event::event.index', $data);
    }

    public function create()
    {
        $data['is_edit'] = false;
        $data['users'] = $this->user->getEmployeeUserList();
        $data['organizationList'] = $this->organization_list->getList();
        $data['departmentList'] = $this->department->getList();
        // $data['provinces'] = Province::pluck('province_name', 'id');
        // $data['districts'] = District::pluck('district_name', 'id');
        return view('event::event.create', $data);
    }

    public function getDistricts(Request $request)
    {
        if ($request->ajax()) {
            $districts = District::where('province_id', $request->province_id)->pluck('district_name', 'id');
            return response()->json($districts);
        }
    }

    public function store(EventRequest $request)
    {
        $data = $request->all();

        try {
            $data['created_by'] = auth()->user()->id;

            if (empty($data['tagged_users'])) {
                if (auth()->user()->user_type == 'employee') {
                    $data['tagged_users'] = [auth()->user()->id];
                } else {
                    $filterArray = ['user_type' => ['employee'], 'model' => 'user'];
                    if (auth()->user()->user_type == 'division_hr') {
                        $filterArray['organization_id'] = optional(auth()->user()->userEmployer)->organization_id;
                    }
                    $data['tagged_users'] = array_keys(employee_helper()->getUserListsByType($filterArray));
                }
            }
            if (!empty($request->organizationId)) {
                $data['organization_id'] = json_encode($request->organizationId);
            }
            if (!empty($request->departmentArray)) {
                $data['department_id'] = json_encode($request->departmentArray);
            }
            if (!empty($request->branchArray)) {
                $data['branch_id'] = json_encode($request->branchArray);
            }
            if (auth()->user()->user_type == 'employee') {
                array_push($data['tagged_users'], auth()->user()->id);
            }
            $data['event_start_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['event_start_date']) : $data['event_start_date'];
            $data['event_end_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['event_end_date']) : $data['event_end_date'];

            $event = $this->event->save($data);
            $this->event->saveTaggedUser($event, $data);
            toastr()->success('Event Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect(route('event.index'));
    }

    public function show()
    {
        return view('event::show');
    }

    public function edit($id)
    {
        $data['is_edit'] = true;
        $data['event'] = $this->event->find($id);
        $data['eventParticipants'] = $this->event->getEmployeeUserList($id);
        // $data['users'] = $this->user->getEmployeeUserList();

        // $filterArray = ['user_type' => ['employee'], 'model' => 'user'];
        // if ($data['event']->createdBy == 'employee') {
        //     $filterArray['organization_id'] = optional(auth()->user()->userEmployer)->organization_id;
        //     $filterArray['organization_id'] = optional(auth()->user()->userEmployer)->organization_id;
        // }

        // if ($data['event']->createdBy == 'division_hr') {
        //     $filterArray['organization_id'] = optional(auth()->user()->userEmployer)->organization_id;
        // }

        $data['organization_id'] = json_decode($data['event']->organization_id);
        $data['branch_id'] = json_decode($data['event']->branch_id);
        $data['department_id'] = json_decode($data['event']->department_id);
        $filter = [$data['organization_id'], $data['department_id'], $data['branch_id']];
        $data['organizationList'] = $this->organization_list->getList();
        $data['branchs'] = $this->branch->branchListMultipleOrganizationwise($data['organization_id']);
        $data['departmentList'] = $this->department->getList();
        $data['users'] = $this->user->getEmployeeUserListByFilter($filter);

        return view('event::event.edit', $data);
    }

    public function update(EventRequest $request, $id)
    {
        $data = $request->all();
        try {
            $data['updated_by'] = auth()->user()->id;
            // $data['event_date_nepali'] = (new DateConverter())->eng_to_nep_convert($data['event_date']);

            $data['event_start_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['event_start_date']) : $data['event_start_date'];
            $data['event_end_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['event_end_date']) : $data['event_end_date'];
            if (!empty($request->organizationId)) {
                $data['organization_id'] = json_encode($request->organizationId);
            }
            if (!empty($request->branchArray)) {
                $data['branch_id'] = json_encode($request->branchArray);
            }
            if (!empty($request->departmentArray)) {
                $data['department_id'] = json_encode($request->departmentArray);
            }
            $this->event->update($id, $data);

            $event  = $this->event->find($id);
            if (!empty($data['tagged_users'])) {
                $data['status'] = 'update';
            } else {
                $data['tagged_users'] = [];
            }
            $event->users()->sync($data['tagged_users']);

            toastr()->success('Event Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect(route('event.index'));
    }

    public function view($id)
    {
        $data['event'] = $this->event->find($id);
        $eventParticipants = $this->event->getEmployeeUserList($id);
        $data['participantNameLists'] = [];
        foreach ($eventParticipants as $key => $value) {
            $full_name = $this->user->getName($value);
            $data['participantNameLists'][$value] = $full_name;
        }
        return view('event::event.view', $data);
    }

    public function destroy($id)
    {
        try {
            $this->event->delete($id);
            toastr()->success('Event Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect(route('event.index'));
    }

    public function getOrganizationEmployee(Request $request)
    {
        // Retrieve filters from the request
        $filters = $request->only(['organization_id', 'branch_id', 'department_id']);

        $users = $this->user->getEmployeeUserListByFilter($filters);

        // Return the response as JSON
        return response()->json($users);
    }


    public function getOrganizationBranch(Request $request)
    {
        $filter = $request->all();
        if (isset($filter['organization_id'])) {
            $branchs = $this->branch->branchListMultipleOrganizationwise($filter['organization_id']);
        }
        return response()->json($branchs);
    }
}
