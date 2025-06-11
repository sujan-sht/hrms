<?php

namespace App\Modules\Admin\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Modules\Event\Entities\Event;
use App\Modules\Notice\Entities\Notice;
use App\Modules\Training\Entities\Training;
use App\Modules\Admin\Entities\SystemReminder;
use App\Modules\Asset\Entities\AssetAllocate;
use App\Modules\Asset\Entities\AssetQuantity;
use App\Modules\Employee\Entities\AssetDetail;
use App\Modules\Employee\Entities\ContractDetail;
use App\Modules\Employee\Entities\DocumentDetail;
use App\Modules\Employee\Entities\Employee;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\Employee\Entities\VisaAndImmigrationDetail;
use App\Modules\Employee\Entities\EmployeePayrollRelatedDetail;
use App\Modules\Onboarding\Entities\ManpowerRequisitionForm;
use App\Modules\Onboarding\Entities\OfferLetter;
use App\Modules\Onboarding\Entities\Onboard;
use App\Modules\Setting\Entities\DeviceManagement;
use Nwidart\Modules\Facades\Module;

class SystemReminderRepository implements SystemReminderInterface
{

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = SystemReminder::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['user_id'])) {
                $query->where('user_id', $filter['user_id']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function create($data)
    {
        return SystemReminder::create($data);
    }

    /**
     *
     */
    public function getSystemReminder($limit = null)
    {
        $authUser = auth()->user();

        $now = Carbon::now();
        $compile_now_date = date('Y-m-d', strtotime($now));
        $compile_end_date = date('Y-m-d', strtotime('+ 30 days', strtotime($now)));

        if ($authUser->user_type == 'employee') {
            $filter['employee_id'] = $authUser->emp_id;
        }

        $filter['date_from'] = $compile_now_date;
        $filter['date_to'] = $compile_end_date;

        // check for probation period end date
        $data['probationModels'] = [];
        // $probationModels = EmployeePayrollRelatedDetail::when(true, function ($query) use ($filter) {
        //     if (isset($filter['date_from'])) {
        //         $query->where('probation_end_date', '>=', $filter['date_from']);
        //     }
        //     if (isset($filter['date_to'])) {
        //         $query->where('probation_end_date', '<=', $filter['date_to']);
        //     }

        //     if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
        //         $query->whereHas('employeeModel', function ($q) {
        //             $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
        //         });
        //     }

        //     if (isset($filter['employee_id'])) {
        //         $query->where('employee_id', $filter['employee_id']);
        //     }
        // })->get();
        // if ($probationModels) {
        //     foreach ($probationModels as $model) {
        //         $date = date('M d, Y', strtotime($model->probation_end_date));
        //         $data['probationModels'][] = [
        //             'icon' => "icon-statistics",
        //             'color' => "success",
        //             'title' => "<b>" . optional($model->employeeModel)->full_name . "</b>'s probation period will be ended on " . $date,
        //             'link' => route('employee.edit', $model->employee_id),
        //             'datetime' => Carbon::parse($model->probation_end_date)
        //         ];
        //     }
        // }

        // check for asset return date
        // $data['assetModels'] = [];
        // $assetModels = AssetDetail::when(array_keys($filter, true), function ($query) use ($filter) {
        //     if (isset($filter['date_from'])) {
        //         $query->where('return_date', '>=', $filter['date_from']);
        //     }
        //     if (isset($filter['date_to'])) {
        //         $query->where('return_date', '<=', $filter['date_to']);
        //     }

        //     if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisior') {
        //         $query->whereHas('employeeModel', function ($q) {
        //             $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
        //         });
        //     }

        //     if (isset($filter['employee_id'])) {
        //         $query->where('employee_id', $filter['employee_id']);
        //     }
        // })->get();
        // if ($assetModels) {
        //     foreach ($assetModels as $model) {
        //         $date = date('M d, Y', strtotime($model->return_date));
        //         $data['assetModels'][] = [
        //             'icon' => "icon-stack-check",
        //             'color' => "primary",
        //             'title' => "<b>" . optional($model->employeeModel)->full_name . "</b>'s asset return date is on " . $date,
        //             'link' => route('employee.view', $model->employee_id),
        //             'datetime' => Carbon::parse($model->return_date)
        //         ];
        //     }
        // }

        $data['assetModels'] = [];
        if (Module::isModuleEnabled('Asset')) {

            $assetModels = AssetAllocate::when(array_keys($filter, true), function ($query) use ($filter) {
                if (isset($filter['date_from'])) {
                    $query->where('return_date', '>=', $filter['date_from']);
                }
                if (isset($filter['date_to'])) {
                    $query->where('return_date', '<=', $filter['date_to']);
                }

                if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                    $query->whereHas('employee', function ($q) {
                        $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                    });
                }

                if (isset($filter['employee_id'])) {
                    $query->where('employee_id', $filter['employee_id']);
                }
            })->get();
            if ($assetModels) {
                foreach ($assetModels as $model) {
                    $date = date('M d, Y', strtotime($model->return_date));
                    $data['assetModels'][] = [
                        'icon' => "icon-stack-check",
                        'color' => "primary",
                        'title' => "<b>" . optional($model->employee)->full_name . "</b>'s" . "</b> Asset" . ' ' . optional($model->asset)->title . "</b> return date is on " . $date,
                        'link' => route('employee.view', $model->employee_id),
                        'datetime' => Carbon::parse($model->return_date)
                    ];
                }
            }
        }

        // check for visa date
        $data['visaModels'] = [];
        $visaModels = VisaAndImmigrationDetail::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['date_from'])) {
                $query->where('visa_expiry_date', '>=', $filter['date_from']);
            }
            if (isset($filter['date_to'])) {
                $query->where('visa_expiry_date', '<=', $filter['date_to']);
            }
            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                $query->whereHas('employeeModel', function ($q) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            }
            if (isset($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
        })->get();
        if ($visaModels) {
            foreach ($visaModels as $model) {
                $date = date('M d, Y', strtotime($model->visa_expiry_date));
                $data['visaModels'][] = [
                    'icon' => "icon-airplane3",
                    'color' => "danger",
                    'title' => "<b>" . optional($model->employeeModel)->full_name . "</b>'s document expiry date is on " . $date,
                    // 'link' => route('employee.view', $model->employee_id),
                    'link' => route('employee.showDocumentDetail', $model->id),
                    'datetime' => Carbon::parse($model->visa_expiry_date)
                ];
            }
        }

        // check for document expiry date
        $data['documentModels'] = [];
        $documentModels = DocumentDetail::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['date_from'])) {
                $query->where('expiry_date', '>=', $filter['date_from']);
            }
            if (isset($filter['date_to'])) {
                $query->where('expiry_date', '<=', $filter['date_to']);
            }
            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                $query->whereHas('employeeModel', function ($q) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            }
            if (isset($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
        })->get();
        if ($documentModels) {
            foreach ($documentModels as $model) {
                $date = date('M d, Y', strtotime($model->expiry_date));
                $data['documentModels'][] = [
                    'icon' => "icon-airplane3",
                    'color' => "danger",
                    'title' => "<b>" . optional($model->employeeModel)->full_name . "</b>'s Document expiry date is on " . $date,
                    'link' => route('employee.view', $model->employee_id),
                    'datetime' => Carbon::parse($model->expiry_date)
                ];
            }
        }

        // check for contract date
        // Employee EmployeePayrollRelatedDetail contract Detail next function added
        $data['contractModels'] = [];
        $contractModels = ContractDetail::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['date_from'])) {
                $query->where('end_to', '>=', $filter['date_from']);
            }
            if (isset($filter['date_to'])) {
                $query->where('end_to', '<=', $filter['date_to']);
            }
            if (isset($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
        })->get();
        if ($contractModels) {
            foreach ($contractModels as $model) {
                $date = date('M d, Y', strtotime($model->end_to));
                $data['contractModels'][] = [
                    'icon' => "icon-certificate",
                    'color' => "teal",
                    'title' => "<b>" . optional($model->employeeModel)->full_name . "</b>'s contract will end on " . $date,
                    'link' => route('employee.view', $model->employee_id),
                    'datetime' => Carbon::parse($model->end_to)
                ];
            }
        }

        // check for event date
        $data['eventModels'] = [];
        $eventModels = Event::when(array_keys($filter, true), function ($query) use ($filter) {
            //divisonhr
            if (auth()->user()->user_type == 'employee') {
                $divisionHrList = (employee_helper()->getParentUserList(['division_hr', 'hr', 'supervisor']));
                $query->whereIn('created_by', array_keys($divisionHrList));
                $query->orWhere('created_by', 1);
            }

            if (isset($filter['date_from'])) {
                $query->where('event_start_date', '>=', $filter['date_from']);
            }
            if (isset($filter['date_to'])) {
                $query->where('event_start_date', '<=', $filter['date_to']);
            }
        })->get();
        if ($eventModels) {
            foreach ($eventModels as $model) {
                $date = date('M d, Y', strtotime($model->event_start_date));
                $data['eventModels'][] = [
                    'icon' => "icon-newspaper",
                    'color' => "warning",
                    'title' => "The event titled <b>" . $model->title . "</b> will start from " . $date,
                    'link' => route('event.index'),
                    'datetime' => Carbon::parse($model->event_start_date)
                ];
            }
        }

        // check for notice date
        $data['noticeModels'] = [];
        $noticeModels = Notice::when(array_keys($filter, true), function ($query) use ($filter) {
            if (in_array(auth()->user()->user_type, ['division_hr', 'employee', 'supervisor'])) {
                $filterArray = [
                    'organization_id' => optional(auth()->user()->userEmployer)->organization_id,
                ];

                $mergeArray = array_merge([
                    'model' => 'user',
                    'user_type' => ['division_hr', 'hr']
                ], $filterArray);
                $userLists = employee_helper()->getUserListsByType($mergeArray);
                $query->whereIn('created_by', array_keys($userLists));
                $query->orWhere('created_by', 1);
            }


            // if (auth()->user()->user_type == 'employee') {
            //     $divisionHrList = (employee_helper()->getParentUserList(['division_hr']));
            //     dd($divisionHrList);
            //     $query->whereIn('created_by', array_keys($divisionHrList));
            //     $query->orWhere('created_by', 1);
            // }

            if (isset($filter['date_from'])) {
                $query->where('notice_date', '>=', $filter['date_from']);
            }
            if (isset($filter['date_to'])) {
                $query->where('notice_date', '<=', $filter['date_to']);
            }
        })->get();
        if ($noticeModels) {
            foreach ($noticeModels as $model) {
                if ($model->type == 1 || $model->notice_date ==  Carbon::now()->toDateString()) {
                    $date = date('M d, Y', strtotime($model->notice_date));
                    $data['noticeModels'][] = [
                        'icon' => "icon-profile",
                        'color' => "warning",
                        'title' => "The notice titled <b>" . $model->title . "</b> is on " . $date,
                        'link' => route('notice.index'),
                        'datetime' => Carbon::parse($model->notice_date)
                    ];
                }
            }
        }

        // check for training date
        $data['trainingModels'] = [];
        if (Module::isModuleEnabled('Training')) {
            $trainingModels = Training::when(array_keys($filter, true), function ($query) use ($filter) {
                if (isset($filter['date_from'])) {
                    $query->where('to_date', '>=', $filter['date_from']);
                }
                if (isset($filter['date_to'])) {
                    $query->where('to_date', '<=', $filter['date_to']);
                }
                if (auth()->user()->user_type == 'division_hr') {
                    $query->where('division_id', optional(auth()->user()->userEmployer)->organization_id);
                }
            })->get();
            if ($trainingModels) {
                foreach ($trainingModels as $model) {
                    $date = date('M d, Y', strtotime($model->to_date));
                    $data['trainingModels'][] = [
                        'icon' => "icon-collaboration",
                        'color' => "indigo",
                        'title' => "The training titled <b>" . $model->title . "</b> will end on " . $date,
                        'link' => route('training.index'),
                        'datetime' => Carbon::parse($model->to_date)
                    ];
                }
            }
        }


        // check for fiscal year end
        $data['fiscalYearModels'] = [];
        $fiscalYearModels = FiscalYearSetup::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['date_from'])) {
                $query->where('end_date_english', '>=', $filter['date_from']);
            }
            if (isset($filter['date_to'])) {
                $query->where('end_date_english', '<=', $filter['date_to']);
            }
        })->get();
        if ($fiscalYearModels) {
            foreach ($fiscalYearModels as $model) {
                $date = date('M d, Y', strtotime($model->end_date_english));
                $data['fiscalYearModels'][] = [
                    'icon' => "icon-calendar52",
                    'color' => "indigo",
                    'title' => "The current fiscal year will end on " . $date,
                    'link' => route('fiscalYearSetup.index'),
                    'datetime' => Carbon::parse($model->end_date_english)
                ];
            }
        }

        // check for mrf position close end date
        $data['mrfPositionCloseModels'] = [];
        $mrfPositionCloseModels = ManpowerRequisitionForm::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['date_from'])) {
                $query->where('end_date', '>=', $filter['date_from']);
            }
            if (isset($filter['date_to'])) {
                $query->where('end_date', '<=', $filter['date_to']);
            }
            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            }
        })->get();
        if ($mrfPositionCloseModels) {
            foreach ($mrfPositionCloseModels as $model) {
                $date = date('M d, Y', strtotime($model->end_date));
                $data['mrfPositionCloseModels'][] = [
                    'icon' => "icon-certificate",
                    'color' => "secondary",
                    'title' => "<b>" . $model->title . "</b> MRF position will be closed on " . $date,
                    'link' => route('mrf.index'),
                    'datetime' => Carbon::parse($model->end_date)
                ];
            }
        }

        // check for onboard join date
        $data['offerLetterModels'] = [];
        if (Module::isModuleEnabled('Onboarding')) {

            $offerLetterModels = OfferLetter::when(array_keys($filter, true), function ($query) use ($filter) {
                if (isset($filter['date_from'])) {
                    $query->where('join_date', '>=', $filter['date_from']);
                }
                if (isset($filter['date_to'])) {
                    $query->where('join_date', '<=', $filter['date_to']);
                }
            })->where('status', 2)->get();
            if ($offerLetterModels) {
                foreach ($offerLetterModels as $model) {
                    $date = date('M d, Y', strtotime($model->join_date));
                    $data['offerLetterModels'][] = [
                        'icon' => "icon-certificate",
                        'color' => "secondary",
                        'title' => "<b>" . optional(optional($model->evaluationModel)->applicantModel)->getFullName() . "</b>  will be onboarded on " . $date,
                        'link' => route('offerLetter.index'),
                        'datetime' => Carbon::parse($model->join_date)
                    ];
                }
            }
        }

        // check for contract period end date
        $data['contractModels'] = [];
        $contractModels = EmployeePayrollRelatedDetail::when(array_keys($filter, true), function ($query) use ($filter, $now) {
            if (isset($filter['date_from'])) {
                $query->where('contract_end_date', '>=', $filter['date_from']);
            }
            if (isset($filter['date_to'])) {
                $compile_end_date = date('Y-m-d', strtotime('+ 7 days', strtotime($now)));
                $query->where('contract_end_date', '<=', $compile_end_date);
            }
            if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
                $query->whereHas('employeeModel', function ($q) {
                    $q->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
                });
            }
            if (isset($filter['employee_id'])) {
                $query->where('employee_id', $filter['employee_id']);
            }
        })->get();
        if ($contractModels) {
            foreach ($contractModels as $model) {
                $date = date('M d, Y', strtotime($model->contract_end_date));
                $data['contractModels'][] = [
                    'icon' => "icon-statistics",
                    'color' => "success",
                    'title' => "<b>" . optional($model->employeeModel)->full_name . "</b>'s contract period will be ended on " . $date,
                    'link' => route('employee.edit', $model->employee_id),
                    'datetime' => Carbon::parse($model->contract_end_date)
                ];
            }
        }

        $data['assetQuantityModels'] = [];
        if (Module::isModuleEnabled('Asset')) {
            $assetQuantityModels = AssetQuantity::when(array_keys($filter, true), function ($query) use ($filter, $now) {

                if (isset($filter['date_to'])) {
                    $query->where('expiry_date', '>=', Carbon::now()->toDateString());
                }

                if (auth()->user()->user_type == 'employee') {
                    $divisionHrList = (employee_helper()->getParentUserList(['hr']));
                    $query->whereIn('created_by', array_keys($divisionHrList));
                    $query->orWhere('created_by', 1);
                }
            })->get();
            if ($assetQuantityModels) {
                foreach ($assetQuantityModels as $model) {
                    $date = date('M d, Y', strtotime($model->expiry_date));
                    $data['assetQuantityModels'][] = [
                        'icon' => "icon-newspaper",
                        'color' => "success",
                        'title' => "Asset " . optional($model->asset)->title . " will be expired on " . $date,
                        'link' => route('assetQuantity.index'),
                        'datetime' => Carbon::parse($date)
                    ];
                }
            }
        }

        $data['deviceManagementModels'] = [];
        if (in_array(auth()->user()->user_type, ['super_admin'])) {
            $deviceManagementModels = DeviceManagement::where('status', 1)->whereHas('attendanceLogs', function ($query) {
                $query->where('date', '!=', date('Y-m-d'));
            })
                ->get();
            if ($deviceManagementModels) {
                foreach ($deviceManagementModels as $model) {
                    $date = date('M d, Y');
                    $data['deviceManagementModels'][] = [
                        'icon' => "icon-iphone",
                        'color' => "warning",
                        'title' => "No data retrieved on device Model " . $model->device_id . " [" . $model->ip_address . "]",
                        'link' => route('deviceManagement.index'),
                        'datetime' => Carbon::parse($date)
                    ];
                }
            }
        }

        // check for employee end date
        $data['employeeModels'] = [];
        // $employeeModels = Employee::when(array_keys($filter, true), function ($query) use ($filter, $now) {
        //     if (isset($filter['date_from'])) {
        //         $query->where('end_date', '>=', $filter['date_from']);
        //     }
        //     if (isset($filter['date_to'])) {
        //         $compile_end_date = date('Y-m-d', strtotime('+ 7 days', strtotime($now)));
        //         $query->where('end_date', '<=', $compile_end_date);
        //     }
        //     if (auth()->user()->user_type == 'division_hr' || auth()->user()->user_type == 'supervisor') {
        //         $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
        //     }
        //     if (isset($filter['employee_id'])) {
        //         $query->where('id', $filter['employee_id']);
        //     }
        // })->get();
        // if ($employeeModels) {
        //     foreach ($employeeModels as $model) {
        //         $date = date('M d, Y', strtotime($model->end_date));
        //         $data['employeeModels'][] = [
        //             'icon' => "icon-calendar52",
        //             'color' => "success",
        //             'title' => "<b>" . $model->full_name . "</b>'s job period will be ended on " . $date,
        //             'link' => route('employee.showJobDetail', $model->id),
        //             'datetime' => Carbon::parse($model->end_date)
        //         ];
        //     }
        // }

        $newArray = array_merge($data['deviceManagementModels'], $data['probationModels'], $data['assetModels'], $data['visaModels'], $data['contractModels'], $data['eventModels'], $data['noticeModels'], $data['trainingModels'], $data['fiscalYearModels'], $data['mrfPositionCloseModels'], $data['offerLetterModels'], $data['assetQuantityModels'], $data['employeeModels']);
        $collection = new Collection($newArray);
        if ($limit) {
            $sortedData = $collection->sortBy('datetime')->take($limit);
        } else {
            $sortedData = $collection->sortBy('datetime');
        }

        return $sortedData;
    }
}
