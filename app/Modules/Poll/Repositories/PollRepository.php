<?php

namespace App\Modules\Poll\Repositories;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Notification\Entities\Notification;
use App\Modules\Poll\Entities\Poll;
use App\Modules\Poll\Entities\PollParticipant;
use App\Modules\Poll\Entities\PollResponse;

class PollRepository implements PollInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Poll::when(true, function ($query) use ($filter) {
            if (auth()->user()->user_type ==  'division_hr') {
                $divisionHrList = [1 => 'Admin'];
                $divisionHrList = $divisionHrList + (employee_helper()->getParentUserList(['division_hr']));
                $divisionHrList = $divisionHrList + (employee_helper()->getParentUserList(['hr']));
                $query->whereIn('created_by', array_keys($divisionHrList));
            }
            if (isset($filter['multiple_option_status']) && !empty($filter['multiple_option_status'])) {
                $query->where('multiple_option_status', $filter['multiple_option_status']);
            }
           
            if (isset($filter['expiry_date']) && !empty($filter['expiry_date'])) {
                if(setting('calendar_type') == 'BS'){
                    $query->where('expiry_date', date_converter()->nep_to_eng_convert($filter['expiry_date']));
                }else{
                    $query->where('expiry_date', $filter['expiry_date']);
                }
            }

            if (isset($filter['status']) && !empty($filter['status'])) {
                if($filter['status'] == 'active'){
                    $query->where('expiry_date', '>=', date('Y-m-d'));
                }elseif($filter['status'] == 'expired'){
                    $query->where('expiry_date', '<', date('Y-m-d'));
                }
            }

            if (isset($filter['checkPollParticipant']) && $filter['checkPollParticipant'] == true) {
                $query->whereHas('pollParticipants', function($q) use ($filter){
                    if(isset($filter['organization_id']) && !empty($filter['organization_id'])){
                        $q->where('organization_id', $filter['organization_id']);
                    }

                    if(isset($filter['department_id']) && !empty($filter['department_id'])){
                        $q->where(function($q) use($filter){
                            $q->where('department_id', $filter['department_id']);
                            $q->orWhere('department_id', null);
                        });
                    }

                    if(isset($filter['level_id']) && !empty($filter['level_id'])){
                        $q->where(function($q) use($filter){
                            $q->where('level_id', $filter['level_id']);
                            $q->orWhere('level_id', null);
                        });
                    }
                });
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return Poll::find($id);
    }

    public function save($data)
    {
        return Poll::create($data);
    }

    public function update($id, $data)
    {
        return Poll::find($id)->update($data);
    }

    public function delete($id)
    {
        return Poll::find($id)->delete();
    }

    public function checkAndUpdateResponse($data)
    {
        return PollResponse::updateOrCreate(
        [
           'poll_id' => $data['poll_id'],
           'employee_id' => $data['employee_id']
        ], $data);
    }

    public function getLatestPoll()
    {
        $query = Poll::query();
        $query->where('start_date', '<=', date('Y-m-d'))->where('expiry_date', '>=', date('Y-m-d'));

        $query->whereHas('pollParticipants', function($q){
            $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);

            $q->where(function($q){
                $q->where('department_id', optional(auth()->user()->userEmployer)->department_id);
                $q->orWhere('department_id', null);
            });

            $q->where(function($q) {
                $q->where('level_id', optional(auth()->user()->userEmployer)->level_id);
                $q->orWhere('level_id', null);
            });
        });
        $poll = $query->orderBy('id', 'desc')->first();
        return $poll;
    }

    public function deletePollResponse($id)
    {
        return PollResponse::where('poll_id', $id)->delete();
    }

    public function checkResponseSubmitted($poll_id, $employee_id)
    {
        return PollResponse::where('poll_id', $poll_id)->where('employee_id', $employee_id)->first();
    }

    public function deletePollParticipants($poll_id)
    {
        return PollParticipant::where('poll_id', $poll_id)->delete();
    }

    public function pollAllocation($data, $pollModel)
    {
        $model = PollParticipant::create($data);
        if($model){
            $this->sendNotification($data, $pollModel);
        }
        return $model;
    }

    public function findPollParticipant($poll_id, $organization_id)
    {
        return PollParticipant::where('poll_id', $poll_id)->where('organization_id', $organization_id)->get();
    }

    public function deletePollParticipant($poll_id, $organization_id)
    {
        return PollParticipant::where('poll_id', $poll_id)->where('organization_id', $organization_id)->delete();
    }
  
    public function updateOrCreatePollParticipant($inputData)
    {
        $pollModel = Poll::where('id', $inputData['poll_id'])->first();

        if(isset($inputData['organization_ids']) && !empty($inputData['organization_ids'])){
            foreach ($inputData['organization_ids'] as $organization_id) {
                $checkParticipant = $this->findPollParticipant($inputData['poll_id'], $organization_id);
                if(isset($checkParticipant) && !empty($checkParticipant)){
                    $this->deletePollParticipant($inputData['poll_id'], $organization_id);
                    $this->createPollParticipant($inputData, $organization_id, $pollModel);
                }else{
                    $this->createPollParticipant($inputData, $organization_id, $pollModel);
                }
            }
        }
    }

    public function createPollParticipant($inputData, $organization_id, $pollModel)
    {
        if ($inputData['type'] == 1) {
            if(isset($inputData['department_ids']) && !empty($inputData['department_ids'])){
                foreach ($inputData['department_ids'] as $department_id) {
                    $data['poll_id'] = $inputData['poll_id'];
                    $data['organization_id'] = $organization_id;
                    $data['department_id'] = $department_id;
                    $this->pollAllocation($data, $pollModel);
                }
            }
        } elseif ($inputData['type'] == 2) {
            if(isset($inputData['level_ids']) && !empty($inputData['level_ids'])){
                foreach ($inputData['level_ids'] as $level_id) {
                    $data['poll_id'] = $inputData['poll_id'];
                    $data['organization_id'] = $organization_id;
                    $data['level_id'] = $level_id;
                    $this->pollAllocation($data, $pollModel);
                }
            }
        }
    }

    public function sendNotification($data, $pollModel) {
        $query = Employee::query();
        $query->has('getUser')->where('status', 1)->where('organization_id', $data['organization_id']);

        if(isset($data['department_id'])){
            $query->where('department_id', $data['department_id']);
        }elseif (isset($data['level_id'])) {
            $query->where('level_id', $data['level_id']);
        }
        $employees = $query->get();
        
        if(isset($employees) && !empty($employees)){
            foreach ($employees as $employee) {
                $notificationData['creator_user_id'] = auth()->user()->id;
                $notificationData['notified_user_id'] = optional($employee->getUser)->id;
                $notificationData['message'] = 'Please submit your response for this poll "'.$pollModel->question. '"';
                $notificationData['link'] = route('poll.viewEmployeeReport', ['status'=>'active']);
                $notificationData['type'] = 'Poll';
                $notificationData['type_id_value'] = $data['poll_id'];
                Notification::create($notificationData);
            }
        }
    }
}
