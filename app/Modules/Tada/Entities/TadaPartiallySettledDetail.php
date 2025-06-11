<?php

namespace App\Modules\Tada\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class TadaPartiallySettledDetail extends Model
{
    protected $table = 'tada_partially_settled_detail';
    protected $fillable = [
        'tada_id',
        'settled_by',
        'settled_date',
        'settled_amt',
        'settled_method',
        'remarks'
    ];

    public function tada()
	{
		return $this->belongsTo(Tada::class, 'tada_id');
	}

    public function settledBy()
    {
        return $this->belongsTo(User::class, 'settled_by');
    }

}
