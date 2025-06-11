<?php

namespace App\Modules\MeetingRoom\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class MeetingRoomDetail extends Model
{

    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class,'booked_by');
    }

}
