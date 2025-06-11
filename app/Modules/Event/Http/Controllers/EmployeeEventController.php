<?php

namespace App\Modules\Event\Http\Controllers;

use App\Modules\User\Repositories\UserInterface;
use App\Modules\Event\Repositories\EventInterface;
use App\Modules\Notification\Repositories\NotificationInterface;
use App\Modules\Holiday\Repositories\HolidayInterface;
use App\Modules\Event\Traits\HtmlTableTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class EmployeeEventController extends Controller
{
    use HtmlTableTrait;

    protected $event;
    /**
     * @var UserInterface
     */
    protected $user;
    /**
     * @var NotificationInterface
     */
    protected $notification;
    /**
     * @var HolidayInterface
     */
    protected $holiday;

    public function __construct(
        EventInterface $event,
        UserInterface $user,
        NotificationInterface $notification,
        HolidayInterface $holiday
    ) {
        $this->event = $event;
        $this->user = $user;
        $this->notification = $notification;
        $this->holiday = $holiday;
    }

    public function index(Request $request)
    {
        $search = $request->all();
        $data['holiday_events'] = $this->event->holidayEvents(20);
        $data['all_dates'] = $this->event->holidayEvents();


        return view('event::event.employee.index_nepali', $data);
    }

    public function indexEnglish(Request $request)
    {
        $search = $request->all();
        $data['holiday_events'] = $this->event->holidayEvents(20);
        $data['all_dates'] = $this->event->holidayEvents();

        return view('event::event.employee.index', $data);
    }

    public function create()
    {
        $data['is_edit'] = false;
        $data['users'] = $this->user->getEmployeeUserList();
        return view('event::event.employee.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $data['created_by'] = auth()->user()->id;
            $data['creator'] = 'employee';
            if (!empty($data['tagged_users'])) {
                $data['tagged_employees'] = json_encode($data['tagged_users']);
            }

            $this->event->save($data);

            if (!empty($data['tagged_users'])) {
                foreach ($data['tagged_users'] as $user_id) {

                    $notification_data = array(
                        'creator_user_id' => auth()->user()->id,
                        'notified_user_id' => $user_id,
                        'message' => auth()->user()->first_name . ' ' . auth()->user()->last_name . ' has created an event.',
                        'link' => route('employee-event.index'),
                        'is_read' => '0',
                    );

                    $this->notification->save($notification_data);
                }
            }

            toastr()->success('Event Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if (isset($data['btn_name']) && $data['btn_name'] == 'submit_new') {
            return redirect(route('employee-event.create'));
        } elseif (isset($data['btn_name']) && $data['btn_name'] == 'submit') {
            return redirect(route('employee-event.index'));
        } else {
            return redirect()->back();
        }
    }

    public function show()
    {
        return view('event::show');
    }

    public function edit($id)
    {
        $data['is_edit'] = true;
        $data['event'] = $this->event->find($id);
        $data['users'] = $this->user->getEmployeeUserList();
        return view('event::event.employee.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            $data['updated_by'] = auth()->user()->id;
            $data['tagged_employees'] = json_encode($data['tagged_users']);

            $this->event->update($id, $data);

            if (!empty($data['tagged_users'])) {
                foreach ($data['tagged_users'] as $user_id) {

                    $notification_data = array(
                        'creator_user_id' => auth()->user()->id,
                        'notified_user_id' => $user_id,
                        'message' => auth()->user()->first_name . ' ' . auth()->user()->last_name . ' has updated an event detail.',
                        'link' => route('employee-event.index'),
                        'is_read' => '0',
                    );

                    $this->notification->save($notification_data);
                }
            }

            toastr()->success('Event Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if ($data['btn_name'] == 'submit_new') {
            return redirect(route('employee-event.create'));
        } else {
            return redirect(route('employee-event.index'));
        }
    }

    public function destroy($id)
    {
        try {
            $this->event->delete($id);
            toastr()->success('Event Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect(route('employee-event.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function view(Request $request, $id)
    {
        $data = $request->all();
        if ($data['type'] == 'event') {
            $event = $this->event->find($id);
            return $this->get_view_event_html($event);
        } else {
            $holiday = $this->holiday->find($id);
            return $this->get_view_holiday_html($holiday);
        }
    }

    public function getDateInfo(Request $request)
    {
        $data = $request->all();
        try {
            $event = $this->event->checkEventByDate($data['edate']);
            $holiday = $this->holiday->getHolidayByDate($data['edate']);
            if (!empty($event) && !empty($holiday)) {
                $html = $this->get_view_event_html($event);
                $html .= $this->get_view_holiday_html($holiday);
                return $html;
            } elseif (!empty($event)) {
                return $this->get_view_event_html($event);
            } else if (!empty($holiday)) {
                return $this->get_view_holiday_html($holiday);
            } else {
                return '<p>Neither Event nor holidays on this date!</p>';
            }
        } catch (\Throwable $t) {
            return 0;
        }
    }

    public function updateDate(Request $request, $id)
    {
        $data = $request->all();
        try {
            $data['updated_by'] = auth()->user()->id;
            $this->event->update($id, $data);

            return 1;
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
