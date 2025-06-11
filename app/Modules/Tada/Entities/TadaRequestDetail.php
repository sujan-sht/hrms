<?php

namespace App\Modules\Tada\Entities;

use Illuminate\Database\Eloquent\Model;

class TadaRequestDetail extends Model
{
    protected $fillable = [
        // 'tada_request_id',
        'type_id',
        'sub_type_id',
        'amount',
        'remark'
    ];

    public function tadaRequest()
	{
		return $this->belongsTo(Tada::class, 'tada_request_id');
	}

    public function tadaType()
	{
		return $this->belongsTo(TadaType::class, 'type_id');
	}

    public function subTadaType()
	{
		return $this->belongsTo(TadaSubType::class, 'sub_type_id');
	}
}

