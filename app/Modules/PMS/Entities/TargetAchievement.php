<?php

namespace App\Modules\PMS\Entities;

use App\Modules\PMS\Repositories\TargetRepository;
use Illuminate\Database\Eloquent\Model;

class TargetAchievement extends Model
{

    protected $fillable = [
        'kra_id',
        'kpi_id',
        'target_id',
        'quarter',
        'target_value',
        'achieved_value',
        'achieved_percent',
        'score',
        'employee_id',
        'remarks'
    ];

    public static function checkAndSetTargetValue($data, $targetModel)
    {
        $model = TargetAchievement::where([
            'employee_id' => $data['employee_id'],
            'target_id' => $data['target_id'],
            'quarter' => $data['quarter'],
        ])->first();

        if ($model) {
            if (is_null($model->achieved_value)) {
                $model->target_value = $data['target_value'];
                $model->save();
            }
        } else {
            $targetRepo = new TargetRepository();
            $getData = [
                'kra_id' => $targetModel->kra_id,
                'kpi_id' => $targetModel->kpi_id,
                'employee_id' => $data['employee_id'],
                'target_id' => $data['target_id'],
                'quarter' => $data['quarter'],
                'target_value' => $data['target_value']
            ];
            $targetRepo->storeAchievedValue($getData);
        }
    }
    public static function statusList()
    {
        return [
            '1' => 'Pending',
            '2' => 'Forward',
            '3' => 'Accept',
            '4' => 'Reject'
        ];
    }
    public function kraInfo()
    {
        return $this->belongsTo(Kra::class, 'kra_id');
    }

    public function kpiInfo()
    {
        return $this->belongsTo(Kpi::class, 'kpi_id');
    }
    public function targetInfo()
    {
        return $this->belongsTo(Target::class, 'target_id');
    }
}
