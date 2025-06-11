<?php

namespace App\Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class SystemReminder extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'date',
        'icon',
        'color',
        'link',
        'description'
    ];
}
