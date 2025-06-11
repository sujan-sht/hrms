<?php

namespace App\Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = [];

    public function fileable()
    {
        return $this->morphTo();
    }

}
