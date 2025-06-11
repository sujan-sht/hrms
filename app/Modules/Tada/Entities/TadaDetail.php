<?php

namespace App\Modules\Tada\Entities;

use Illuminate\Database\Eloquent\Model;

class TadaDetail extends Model
{
    protected $fillable = [
        'tada_id',
        'type_id',
        'amount',
        'remark'
    ];

    public function tada()
    {
        return $this->belongsTo(Tada::class, 'tada_id');
    }

    public function tadaType()
    {
        return $this->belongsTo(TadaType::class, 'type_id');
    }
}
