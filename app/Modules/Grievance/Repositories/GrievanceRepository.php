<?php

namespace App\Modules\Grievance\Repositories;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Event\Entities\Event;
use App\Modules\Event\Entities\EventParticipant;
use App\Modules\Grievance\Entities\Grievance;
use App\Modules\Holiday\Entities\Holiday;
use App\Modules\Notification\Entities\Notification;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class GrievanceRepository implements GrievanceInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $query = Grievance::query();
        $userModel = Auth::user();
        
        if (auth()->user()->user_type == 'employee' || auth()->user()->user_type == 'supervisor' || auth()->user()->user_type == 'division_hr') {
            $query->where('created_by', $userModel->id);
        } 
        // elseif (auth()->user()->user_type == 'division_hr') {
        //     $query->whereHas('user.userEmployer', function ($query) use ($userModel) {
        //         $orgn_id = optional($userModel->userEmployer)->organization_id;
        //         $query->where('organization_id', $orgn_id);
        //     });
        // } 

        // elseif (auth()->user()->user_type == 'supervisor') {
        //     $authUserId = array(intval($userModel->id));
        //     $subordinateUserIds = Employee::getSubordinateUserIds($userModel->id);
        //     $userIds = array_merge($authUserId, $subordinateUserIds);
        //     $query->whereIn('created_by', $userIds);
        // }

        // elseif (auth()->user()->user_type == 'hr') {
        //     $query->whereHas('user.userEmployer', function ($query) use ($userModel) {
        //         $orgn_id = optional($userModel->userEmployer)->organization_id;
        //         $query->where('organization_id', $orgn_id);
        //     });
        //     $query->orWhere('created_by', $userModel->id);

        // }
        $result =  $query->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return Grievance::find($id);
    }

    public function getList()
    {
        $result = Grievance::pluck('title', 'id');
        return $result;
    }

    public function save($data)
    {
        return Grievance::create($data);
    }


    public function update($id, $data)
    {
        $result = Grievance::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        $event = $this->find($id);
        EventParticipant::where('event_id', $event->id)->delete();
        return $event->delete();
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
            $notificationData['message'] = "The event titled " . $model->title . " has been created" . " by " . $authorName;
            $notificationData['link'] = route('event.view', $model->id);
            $notificationData['type'] = 'event';
            $notificationData['type_id_value'] = $model->id;
            Notification::create($notificationData);
        }

        return true;
    }

    public function upload($file)
    {
        $imageName = $file->getClientOriginalName();
        $fileName = $imageName;

        $file->move(public_path() . Grievance::FILE_PATH, $fileName);
        return $fileName;
    }
}
