<?php

namespace App\Modules\Api\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Api\Service\UserDeviceRepository;
use Illuminate\Http\Request;
// use App\Repositories\UserDeviceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Ladumor\OneSignal\OneSignal;

/**
 * Class UserDeviceAPIController
 */
class OneSignalController extends ApiController
{
    /** @var UserDeviceRepository */
    public $userDeviceRepo;

    /**
     * UserDeviceAPIController constructor.
     * @param  UserDeviceRepository  $userDeviceRepo
     */
    public function __construct(UserDeviceRepository $userDeviceRepo)
    {
        $this->userDeviceRepo = $userDeviceRepo;
    }

    /**
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function registerDevice(Request $request)
    {
        $this->userDeviceRepo->updateOrCreate($request->all());

        return $this->sendSuccess('The device has been registered successfully.');
    }

    /**
     * @param $playerId
     *
     * @return JsonResponse
     */
    public function updateNotificationStatus($playerId)
    {
        $this->userDeviceRepo->updateStatus($playerId);

        return $this->sendSuccess('The notification status has been updated successfully.');
    }

    public function storeNotification(Request $request)
    {
        // dd('asd');'735e9529-d185-476a-9544-3155fa100a58'
        $fields['include_player_ids'] = [$request->device_id];
        $fields['isIos'] = true;
        $fields['isAndroid'] = true;
        $message = $request->msg;
        $oneSignal =  OneSignal::sendPush($fields, $message);

        return  $this->respond([
            'status' => true,
            'data' => $oneSignal['id']
        ]);
        // dd($oneSignal['id']);
    }

    public function getAllNotifications(Request $request)
    {
        $notifications = OneSignal::getNotifications();
        return  $this->respond([
            'status' => true,
            'data' => $notifications
        ]);
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    private function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }
}
