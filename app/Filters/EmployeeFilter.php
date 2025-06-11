<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * Class EmployeeFilter
 * @package App\Filters
 */
class EmployeeFilter extends BaseFilter
{
    public function organizationId($id): Builder
    {
        return $this->filterProcess($this->query, ['organization_id' => $id]);
    }

    public function unitId(int $id): Builder
    {
        return $this->query->where("unit_id_value", $id);
    }

    public function roleName(string $role): Builder
    {
        return $this->query->whereHas("user", fn($q) => $q->where("user_type", $role));
    }

    public function ageRange(int $from, int $to): Builder
    {
        $todayAd = now()->toDateString();
        return $this->query->whereRaw("TIMESTAMPDIFF(YEAR, dob, ?) BETWEEN ? AND ?", [$todayAd, $from, $to]);
    }

    public function tenureRange(string $from, string $to): Builder
    {
        return $this->query->whereBetween("nepali_join_date", [$from, $to]);
    }

    public function levelId(int $id): Builder
    {
        return $this->filterProcess($this->query, ['level_id' => $id]);
    }

    public function name(string $name): Builder
    {
        return $this->query->where(function ($q) use ($name) {
            $check = Str::contains($name, " ");
            if ($check) {
                $fullname = explode(" ", $name);
                if (count($fullname) == 2) {
                    $q->whereRaw("concat(first_name, ' ', last_name) like ?", ['%' . $name . '%']);
                } else {
                    $q->whereRaw("concat(first_name, ' ', middle_name, ' ', last_name) like ?", ['%' . $name . '%']);
                }
            } else {
                $q->where('first_name', 'like', '%' . $name . '%')
                    ->orWhere('middle_name', 'like', '%' . $name . '%')
                    ->orWhere('last_name', 'like', '%' . $name . '%');
            }
        });
    }



    public function email(string $email): Builder
    {
        return $this->query->where("personal_email", $email)
            ->orWhere("official_email", $email);
    }

    public function phone(string $phone): Builder
    {
        return $this->query->where("mobile", $phone);
    }

    public function employeeCode(string $code): Builder
    {
        return $this->query->where("employee_code", $code);
    }

    public function designationId(int $id): Builder
    {
        return $this->filterProcess($this->query, ['designation_id' => $id]);
    }

    public function functionId(int $id): Builder
    {
        return $this->query->where('function_id', $id);
    }

    public function departmentId(int $id): Builder
    {
        return $this->filterProcess($this->query, ['department_id' => $id]);
    }

    public function branchId($id): Builder
    {
        return $this->filterProcess($this->query, ['branch_id' => $id]);
    }

    public function employeeId(int $id): Builder
    {
        return $this->query->where("id", $id);
    }

    public function empIds(array $ids): Builder
    {
        return $this->query->whereIn("id", $ids);
    }

    public function jobStatus(string $status): Builder
    {
        return $this->query->where("job_status", $status);
    }

    public function insurance(array $filters): Builder
    {
        return $this->query->whereHas("insuranceDetail", function ($q) use ($filters) {
            if (isset($filters['gpa_enable'])) {
                $q->where("gpa_enable", $filters['gpa_enable']);
            }
            if (isset($filters['gmi_enable'])) {
                $q->where("gmi_enable", $filters['gmi_enable']);
            }
        });
    }

    public function permanentProvince(string $province): Builder
    {
        return $this->query->where("permanentprovince", $province);
    }

    public function tenureNepDate(array $filters): Builder
    {
        return $this->query->where(function ($q) use ($filters) {
            if (isset($filters[0])) {
                $from_date = date_converter()->nep_to_eng_convert($filters[0]);
                if (isset($filters[1])) {
                    $to_date = date_converter()->nep_to_eng_convert($filters[1]);
                    $q->where('join_date', '>=', $from_date);
                    $q->where('join_date', '<=', $to_date);
                } else {
                    $q->where('join_date', '=', $from_date);
                }
            }
        });
    }

    public function tenureEngDate(array $filters): Builder
    {
        return $this->query->where(function ($q) use ($filters) {
            if (isset($filters[0])) {
                if (isset($filters[1])) {
                    $q->where('join_date', '>=', $filters[0]);
                    $q->where('join_date', '<=', $filters[1]);
                } else {
                    $q->where('join_date', '=', $filters[0]);
                }
            }
        });
    }
}
