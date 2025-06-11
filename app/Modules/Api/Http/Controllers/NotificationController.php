<?php

namespace App\Modules\Api\Http\Controllers;

use App\Modules\Notification\Repositories\NotificationInterface;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserDeviceAPIController
 */
class NotificationController extends ApiController
{
    protected $notification;

    public function __construct(NotificationInterface $notification)
    {
        $this->notification = $notification;
    }

    public function notifications(){
        $user = Auth::user();
        $id = $user->id;

        $notifications = $this->notification->findAll($id);

        return  $this->respond([
            'status' => true,
            'data' => $notifications
        ]);
    }
}
