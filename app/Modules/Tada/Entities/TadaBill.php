<?php

namespace App\Modules\Tada\Entities;

use Illuminate\Database\Eloquent\Model;

class TadaBill extends Model
{
	const FILE_PATH = '/uploads/tada/bills/';
	
	protected $fillable = [

		'tada_id',
		'image_src',
		'mime_type'
	];

	public function tada()
	{
		return $this->belongsTo(Tada::class, 'tada_id');
	}
}
