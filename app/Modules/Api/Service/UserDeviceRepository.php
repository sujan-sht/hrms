<?php

namespace App\Modules\Api\Service;


// use App\Models\UserDevice;

use App\Modules\Api\Entities\UserDevice;
use App\Modules\Employee\Entities\Employee;
use Exception;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Ladumor\OneSignal\OneSignal;

/**
 * Class RegisterDeviceRepository
 */
class UserDeviceRepository
{

    /**
     * @param $input
     *
     */
    public function updateOrCreate($input)
    {
        try {
            return UserDevice::updateOrCreate([
                'os_player_id' => $input['os_player_id'],
            ], [
                'user_id' => auth()->user()->id,
                'os_player_id' => $input['os_player_id'],
                'device_type' => $input['device_type']
            ]);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param $playerId
     *
     * @return bool
     */
    public function updateStatus($playerId): bool
    {
        $userDevice = UserDevice::whereOsPlayerId($playerId)->first();
        $userDevice->update(['is_active' => !$userDevice->is_active]);

        return true;
    }

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
