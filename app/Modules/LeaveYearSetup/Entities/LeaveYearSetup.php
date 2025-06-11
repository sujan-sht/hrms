<?php

namespace App\Modules\LeaveYearSetup\Entities;

use Illuminate\Database\Eloquent\Model;

class LeaveYearSetup extends Model
{

    protected $fillable = [
        'leave_year',
        'leave_year_english',
        'start_date',
        'end_date',
        'start_date_english',
        'end_date_english',
        'status',
        'calender_type'
    ];


    public static function currentLeaveYear()
    {
        return LeaveYearSetup::where('status', 1)->first();
    }

    public static function previousLeaveYear()
    {
        return LeaveYearSetup::where('id', '<', getCurrentLeaveYearId())->orderBy('id', 'desc')->first();
    }
}
