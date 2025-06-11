<?php

namespace App\Modules\Training\Entities;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Department;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $casts = [
        'month' => 'json',
    ];

    protected $fillable = [
        'division_id',
        'title',
        'description',
        'facilitator_name',
        'facilitator',
        'location',
        'type',
        'from_date',
        'to_date',
        'no_of_days',
        'planned_budget',
        'no_of_participants',
        'no_of_mandays',
        'actual_expense_incurred',
        'month',
        'no_of_employee',
        'status',
        'date',
        'fiscal_year_id',
        'functional_type',
        'training_for',
        'full_marks',
        'targeted_participant',
        'department_id',
        'frequency',
        'pax_training'
    ];

    // public function division()
    // {
    //     return $this->belongsTo(Dropdown::class, 'division_id', 'id');
    // }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function monthInfo()
    {
        return $this->belongsTo(Dropdown::class, 'month');
    }

    public function fiscalYearInfo()
    {
        return $this->belongsTo(FiscalYearSetup::class, 'fiscal_year_id');
    }

    public function trainer()
    {
        return $this->hasOne(TrainingTrainer::class, 'training_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'division_id', 'id');
    }

    public function getMonth()
    {
        $months = $this->month;
        $result = [];
        if (isset($months) && !empty($months)) {
            $monthList = (new DateConverter())->getNepMonths();
            foreach ($months as $month) {
                $result[] = $monthList[$month];
            }
        }

        return implode(", ", $result);
    }

    public static function getCount()
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $result = Training::where('division_id', optional($authUser->userEmployer)->organization_id)->count();
        } else {
            $result = Training::count();
        }

        return $result;
    }
}
