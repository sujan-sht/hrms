<?php

namespace App\Modules\Onboarding\Entities;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $appends = array('full_name');

    const RESUME_PATH = 'uploads/onboarding/applicant/resume';
    const COVER_LETTER_PATH = 'uploads/onboarding/applicant/cover_letter';

    protected $fillable = [
        'manpower_requisition_form_id',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'city',
        'province',
        'mobile',
        'email',
        'gender',
        'source',
        'experience',
        'expected_salary',
        'skills',
        'resume',
        'cover_letter',
        'status',
        'latest_stage',
        'external_id',
        'external_comment',
        'academic_qualification',
        'current_organization',
        'current_designation',
        'reference_name',
        'reference_position',
        'reference_contact_number',
        'created_by',
        'updated_by'
    ];

    /**
     * Relation with MRF
     */
    public function mrfModel()
    {
        return $this->belongsTo(ManpowerRequisitionForm::class, 'manpower_requisition_form_id');
    }

    /**
     *
     */
    public function getResume()
    {
        return asset(Self::RESUME_PATH . '/' . $this->resume);
    }

    /**
     *
     */
    public function getCoverLetter()
    {
        return asset(Self::COVER_LETTER_PATH . '/' . $this->cover_letter);
    }

    /**
     * Get fullname
     */
    public function getFullName()
    {
        if ($this->middle_name) {
            $fullName = $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        } else {
            $fullName = $this->first_name . ' ' . $this->last_name;
        }

        return $fullName;
    }

    /**
     * Get fullname Attribute
     */
    protected function getFullNameAttribute()
    {
        if (isset($this->middle_name)) {
            $fullName = $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        } else {
            $fullName = $this->first_name . ' ' . $this->last_name;
        }

        return $fullName;
    }

    /**
     * Get fullname Attribute
     */
    protected function getFullAddressAttribute()
    {
        $fullAddress = $this->address;

        if (isset($this->city)) {
            $fullAddress = $fullAddress . ', ' . $this->city;
        }

        if (isset($this->province)) {
            $fullAddress = $fullAddress . ', ' . $this->province;
        }

        return $fullAddress;
    }

    /**
     *
     */
    public function getGender()
    {
        $list = Self::genderList();
        return $list[$this->gender];
    }

    /**
     *
     */
    public function getSource()
    {
        $list = Self::sourceList();
        return $list[$this->source ?? 1];
    }

    /**
     *
     */
    public function getStatusWithColor()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '2':
                $color = 'primary';
                break;
            case '3':
                $color = 'success';
                break;
            case '4':
                $color = 'danger';
                break;
            default:
                $color = 'secondary';
                break;
        }

        return [
            'status' => $list[$this->status],
            'color' => $color
        ];
    }

    /**
     *
     */
    public function getStage()
    {
        $list = Self::stageList();
        return $list[$this->latest_stage];
    }

    public static function getSourceCount($source)
    {
        if (auth()->user()->user_type == 'division_hr') {
            $query = Applicant::query();
            $query->where('source', $source);
            $query->whereHas('mrfModel', function ($q) {
                $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            });
            $result = $query->count();
        } else {
            $result = Applicant::where('source', $source)->count();
        }

        return $result;
    }

    /**
     * Gender list
     */
    public static function genderList()
    {
        return [
            '1' => 'Male',
            '2' => 'Female'
        ];
    }

    /**
     * Source list
     */
    public static function sourceList()
    {
        return [
            '1' => 'Internal',
            '2' => 'Referer',
            '3' => 'LinkedIn',
            '4' => 'Mero Jobs',
            '5' => 'Website',
            '6' => 'Others'
        ];
    }

    /**
     * Status list
     */
    public static function statusList()
    {
        return [
            '1' => 'Pending',
            '2' => 'Shortlisted',
            '3' => 'Hired',
            '4' => 'Waiting',
            '5' => 'Rejected'
        ];
    }

    /**
     * Stage list
     */
    public static function stageList()
    {
        return [
            '1' => 'Create MRF',
            '2' => 'Vacancy Published',
            '3' => 'CV Pull',
            '4' => 'Shortlisted',
            '5' => 'Hired'
        ];
    }
}
