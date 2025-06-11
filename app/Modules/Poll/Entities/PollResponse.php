<?php

namespace App\Modules\Poll\Entities;

use App\Modules\Poll\Entities\Poll;
use Illuminate\Database\Eloquent\Model;

class PollResponse extends Model
{

    protected $fillable = [
        'poll_id',
        'employee_id',
        'poll_option_id'
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }

    public function options()
    {
        return $this->belongsTo(PollOption::class, 'poll_option_id');
    }
    
}
