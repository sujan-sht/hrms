<?php

namespace App\Modules\Poll\Entities;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    protected $fillable = [
        'poll_id',
        'option'
    ];

    public function responses()
    {
        return $this->hasMany(PollResponse::class, 'poll_option_id','id');
    }

    public function getEmployeePollResponse($employee_id)
    {
        return $this->responses->where('employee_id', $employee_id);
    }
    
}
