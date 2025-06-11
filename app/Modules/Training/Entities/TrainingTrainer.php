<?php

namespace App\Modules\Training\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingTrainer extends Model
{

    protected $fillable = ['training_id', 'full_name', 'email', 'phone', 'remark'];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }
}
