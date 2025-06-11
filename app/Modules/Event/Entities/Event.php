<?php

namespace App\Modules\Event\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'note',
        'event_date',
        'event_date_nepali',
        'event_time',
        'tagged_employees',
        'location',
        'status',
        'created_by',
        'updated_by',
        'creator',
        'event_start_date',
        'event_end_date',
        'organization_id',
        'department_id',
        'branch_id'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_participants', 'event_id', 'user_id');
        // return $this->hasMany(EventParticipant::class, 'event_id');

    }
}
