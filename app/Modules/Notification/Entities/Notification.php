<?php

namespace App\Modules\Notification\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [

        'creator_user_id',
        'notified_user_id',
        'message',
        'link',
        'type',
        'type_id_value',
        'is_read',

    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
    }
//if type == notice
    public function notice()
    {
        return $this->belongsTo(Notice::class, 'type_id_value');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'notified_user_id', 'id');
    }
}
