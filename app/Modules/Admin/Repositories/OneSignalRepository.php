<?php

namespace App\Modules\Admin\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Modules\Employee\Entities\Employee;

use Ladumor\OneSignal\OneSignal;


class OneSignalRepository implements OneSignalInterface
{

    public function sendNotification($model)
    {
        $employeeModel = Employee::find($model->employee_id);
        $userModel = optional($employeeModel->getUser);
        $fields['include_player_ids'] = [optional($userModel->device)->os_player_id];
        $fields['isIos'] = true;
        $fields['isAndroid'] = true;
        $message = 'Leave Request';
        $oneSignal =  OneSignal::sendPush($fields, $message);
        return true;
    }
}
