<?php

namespace App\Modules\Admin\Repositories;

interface OneSignalInterface
{
    public function sendNotification($model);
}
