<?php

namespace App\Modules\Event\Repositories;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Event\Entities\Event;
use App\Modules\Event\Entities\EventParticipant;
use App\Modules\Holiday\Entities\Holiday;
use App\Modules\Notification\Entities\Notification;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;

class EventRepository implements EventInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'event_start_date', 'sort' => 'DESC'], $status = [0, 1])
    {
        $query = Event::query();
        if (auth()->user()->user_type == 'employee') {
            $query->whereHas('users', function ($q) {
                $q->where('users.id', auth()->user()->id);
            });
            // $query->orDoesnthave('users');
        }

        if (auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'division_hr') {
            $divisionHrList = [1 => 'Admin'];
            $divisionHrList = $divisionHrList + (employee_helper()->getParentUserList(['division_hr', 'supervisor', 'hr']));
            $query->whereIn('created_by', array_keys($divisionHrList));
        }

        if (isset($filter['start']) && !empty(['start'])) {
            $query->whereDate('event_start_date', '>=', $filter['start']);
        }

        if (isset($filter['end']) && !empty(['end'])) {
            $query->whereDate('event_start_date', '<=', $filter['end']);
        }

        $result =  $query->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return Event::find($id);
    }

    public function getList()
    {
        $result = Event::pluck('title', 'id');
        return $result;
    }

    public function save($data)
    {
        return Event::create($data);
    }


    public function update($id, $data)
    {
        $result = Event::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        $event = $this->find($id);
        // $event->users()->delete();
        EventParticipant::where('event_id', $event->id)->delete();
        return $event->delete();
        // return Event::destroy($id);
    }

    public function saveTaggedUser($event, $data = [])
    {
        if (auth()->user()->user_type == "employee") {
            if (empty($data['tagged_users'])) {
                $data['tagged_users'] = [auth()->user()->id];
            } else {
                if (!in_array(auth()->user()->id, $data['tagged_users'])) {
                    array_push($data['tagged_users'], (string)auth()->user()->id);
                }
            }
            $event->users()->sync($data['tagged_users']);
            $this->sendMailNotification($event);
        } elseif (!empty($data['tagged_users'])) {
            $event->users()->sync($data['tagged_users']);
            $this->sendMailNotification($event);
        }
    }


    public function getEmployeeUserList($eventID)
    {
        $result = EventParticipant::where('event_id', $eventID)->pluck('user_id');
        return $result;
    }

    public function getLatestEvent()
    {
        $events = Event::when(true, function ($query) {
            $now = Carbon::now();
            $compile_end_date = Carbon::now()->addDays(90);

            $query->whereDate('event_start_date', '>=', $now);

            if (auth()->user()->user_type == 'employee') {
                $query->doesnthave('users');
                $query->orWhereHas('users', function (Builder $qry) {
                    $qry->where('users.id', auth()->user()->id);
                });
            }

            // $query->where(function (Builder $qry) use($compile_end_date) {
            //     $qry->whereDate('event_end_date', '<=', $compile_end_date);
            //     $qry->orWhereNull('event_end_date');
            // });
        })
            ->orderBy('event_start_date', 'DESC')
            ->get();

        $returnEventArray = [];
        foreach ($events as $key => $value) {
            $returnEventArray[] = [
                'id' => $value['id'],
                'title' => $value['title'],
                'date' => $value['event_start_date'],
                'type' => 'event'
            ];
        }

        return $returnEventArray;
        //ranjan
    }

    public function getUpcomingEvents()
    {
        $now = Carbon::now();
        return Event::where('event_start_date', '>=', $now)->get();
    }

    public function holidayEvents($limit = null, $filter = [])
    {
        $user_id = auth()->user()->id;
        $gender = optional(auth()->user()->userEmployer)->gender ?? 1;

        $events = Event::where('status', 1)
            ->where(function ($q) use ($user_id) {
                $q->whereNotNull(DB::raw('JSON_SEARCH(tagged_employees,"all","' . $user_id . '")'));
                $q->orWhere('created_by', $user_id);
            })
            ->selectRaw('"event" as type, id, title, event_date, event_time, description, note , location, created_by, updated_by, creator, JSON_SEARCH(tagged_employees,"all","' . $user_id . '") as emp_exist');

        $holidays = Holiday::
            // where('type', 0)
            //     ->orWhere(function ($q) use ($gender) {
            //         $q->where(['type' => 1, 'type_value' => $gender]);
            //     })
            // ->selectRaw('"holiday" as type, id, title, date as event_date, "" as event_time, "" as description, "" as note, "" as location, created_by, updated_by, "admin" as creator, "" as emp_exist');
            selectRaw('"holiday" as type, id, title, "" as event_date, "" as event_time, "" as description, "" as note, "" as location,"" as created_by, "" as updated_by, "admin" as creator, "" as emp_exist');
        $unions = $events->unionAll($holidays);
        // dd($unions->toSql());

        $result = DB::table(DB::raw("({$unions->toSql()}) AS s"))
            ->mergeBindings($unions->getQuery())
            ->when(array_keys($filter, true), function ($query) use ($filter) {})
            ->selectRaw('s.type, s.id, s.title, s.event_date, s.event_time, s.description, s.note , s.location, s.created_by, s.updated_by, s.creator')
            ->orderBy('s.event_date', 'DESC')
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function checkEventByDate($date)
    {
        $user_id = auth()->user()->id;

        $event = Event::where('status', 1)->where('event_start_date', $date)
            ->where(function ($q) use ($user_id) {
                $q->whereNotNull(DB::raw('JSON_SEARCH(tagged_employees,"all","' . $user_id . '")'));
                $q->orWhere('created_by', $user_id);
            })->first();

        return $event;
    }

    public function datewiseeventlist($date, $calender_type = 0)
    {
        if ($calender_type == 1) {
            return Event::where(DB::raw("(DATE_FORMAT(nepali_data,'%Y-%m-%d'))"), $date)->get();
        } elseif ($calender_type == 0) {
            return Event::where(DB::raw("(DATE_FORMAT(event_start_date,'%Y-%m-%d'))"), $date)->get();
        }
    }

    public function sendMailNotification($model)
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'super_admin') {
            $authorName = $authUser->first_name;
        } else {
            $authorName = optional($authUser->userEmployer)->full_name;
        }

        $userEmployeeLists = $this->getEmployeeUserList($model->id);
        foreach ($userEmployeeLists as $userId) {
            $notificationData['creator_user_id'] = $authUser->id;
            $notificationData['notified_user_id'] = $userId;
            $notificationData['message'] = "The event titled " . $model->title . " has been created" . " by " . $authUser->user_type . ' ' . "Sub-Function";
            $notificationData['link'] = route('event.view', $model->id);
            $notificationData['type'] = 'event';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);
        }

        return true;
    }

    public function getEventByUserType()
    {
        $query = Event::query();
        if (auth()->user()->user_type == 'employee') {
            $query->whereHas('users', function ($q) {
                $q->where('users.id', auth()->user()->id);
            });
            $query->orDoesnthave('users');
        }
        $result = $query->get();

        return $result;
    }
}
