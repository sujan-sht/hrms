<?php

namespace App\Modules\Notice\Entities;

use Illuminate\Database\Eloquent\Model;

class NoticeStatus extends Model
{
    protected $fillable = [
        
        'notice_id',
        'status'
        
    ];
}
