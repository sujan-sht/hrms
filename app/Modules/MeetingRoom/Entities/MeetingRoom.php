<?php

namespace App\Modules\MeetingRoom\Entities;


use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model
{
    const FILE_PATH = '/uploads/meetingroom/';

    protected $guarded = [];


    public function getStatusAttribute($attribute)
    {
        return in_array($attribute ?? null, [0, 1])
            ? [
                0 => 'Vacant',
                1 => 'Occupied'
            ][$attribute] : null;
    }

    public function meetingRoomDetailInfo()
    {
        return $this->hasMany(MeetingRoomDetail::class,'room_id');
    }

    public function getTodayBooking()
    {
        return $this->hasMany(MeetingRoomDetail::class,'room_id')->where('date',today())->orderBy('start_time','ASC');
    }
}
