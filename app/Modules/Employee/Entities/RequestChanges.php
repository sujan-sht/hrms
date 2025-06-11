<?php

namespace App\Modules\Employee\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Employee\Entities\BankDetail;
use App\Modules\Employee\Entities\AwardDetail;
use App\Modules\Employee\Entities\SkillDetail;
use App\Modules\Employee\Entities\FamilyDetail;
use App\Modules\Employee\Entities\BenefitDetail;
use App\Modules\Employee\Entities\MedicalDetail;
use App\Modules\Employee\Entities\ContractDetail;
use App\Modules\Employee\Entities\EducationDetail;
use App\Modules\Employee\Entities\PreviousJobDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Employee\Entities\VisaAndImmigrationDetail;
use App\Modules\Employee\Entities\ResearchAndPublicationDetail;

class RequestChanges extends Model
{
    protected $fillable = [
        'employee_id',
        'old_first_name',
        'old_middle_name',
        'old_last_name',
        'old_mobile',
        'old_phone',
        'old_personal_email',
        'old_permanent_address',
        'old_temporary_address',

        'old_national_id',
        'old_passport_no',
        'old_telephone',
        'old_official_email',
        'old_marital_status',
        'old_citizenship_no',
        'old_blood_group',
        'old_ethnicity',
        'old_language',

        'new_first_name',
        'new_middle_name',
        'new_last_name',
        'new_mobile',
        'new_phone',
        'new_personal_email',
        'new_permanent_address',
        'new_temporary_address',

        'new_national_id',
        'new_passport_no',
        'new_telephone',
        'new_official_email',
        'new_marital_status',
        'new_citizenship_no',
        'new_blood_group',
        'new_ethnicity',
        'new_language',

        'entity',
        'old_entity_id',
        'new_entity_id',
        'status',
        'approved_by',
        'change_date',
        'approved_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approved_info()
    {
        return $this->belongsTo(User::class, "approved_by");
    }

    public function oldEntity()
    {
        return $this->belongsTo("App\\Modules\\Employee\\Entities\\{$this->entity}", 'old_entity_id');
    }

    public function newEntity()
    {
        return $this->belongsTo("App\\Modules\\Employee\\Entities\\{$this->entity}", 'new_entity_id');
    }
    public function oldBloodGroup()
    {
        return $this->belongsTo(Dropdown::class, 'old_blood_group', 'id');
    }
    public function newBloodGroup()
    {
        return $this->belongsTo(Dropdown::class, 'new_blood_group', 'id');
    }
    public function oldMaritalStatus()
    {
        return $this->belongsTo(Dropdown::class, 'old_marital_status');
    }
    public function newMaritalStatus()
    {
        return $this->belongsTo(Dropdown::class, 'new_marital_status');
    }
}
