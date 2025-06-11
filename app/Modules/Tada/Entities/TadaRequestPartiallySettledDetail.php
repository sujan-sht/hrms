<?php

namespace App\Modules\Tada\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class TadaRequestPartiallySettledDetail extends Model
{
    protected $table = 'tada_request_partially_settled_details';

    protected $fillable = [
        'tada_request_id',
        'settled_by',
        'settled_date',
        'settled_amt',
        'settled_method',
        'remarks'
    ];


    public function tada()
	{
		return $this->belongsTo(TadaRequest::class, 'tada_id');
	}

    public function settledBy()
    {
        return $this->belongsTo(User::class, 'settled_by');
    }

}
