<?php

namespace App\Modules\Appraisal\Http\Controllers;

use App\Modules\Appraisal\Entities\Appraisal;
use App\Modules\Appraisal\Entities\AppraisalDevelopmentPlan;
use App\Modules\Appraisal\Entities\AppraisalResponse;
use App\Modules\Appraisal\Repositories\AppraisalInterface;
use App\Modules\Appraisal\Repositories\QuestionnaireInterface;
use App\Modules\Appraisal\Repositories\RatingScaleInterface;
use App\Modules\Appraisal\Repositories\ScoreInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Setting\Repositories\SettingInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AppraisalReportController extends Controller
{
    protected $appraisal;
    protected $employee;
    protected $questionnaire;
    protected $score;
    protected $setting;
    protected $ratingScale;

    public function __construct(AppraisalInterface $appraisal, EmployeeInterface $employee, QuestionnaireInterface $questionnaire, ScoreInterface $score, SettingInterface $setting, RatingScaleInterface $ratingScale)
    {
        $this->appraisal = $appraisal;
        $this->employee = $employee;
        $this->score = $score;
        $this->setting = $setting;
        $this->ratingScale = $ratingScale;
    }

    public function index(Request $request)
    {
        $data['filter']  = $request->all();
        $data['employee'] = $this->employee->findAll()->pluck('full_name', 'id');
        $data['appraisals'] = $this->appraisal->findAll($limit = 50, $data['filter']);
        $data['selected_employee'] = $request->emp_id;
        if (auth()->user()->user_type == 'employee') {
            $data['selected_employee'] = auth()->user()->emp_id;
        }
        return view('appraisal::appraisal-report.index', $data);
    }

    public function performanceEvaluationSummary(Request $request)
    {
        $filter = $request->all();
        $data['employee'] = $this->employee->findAll()->pluck('full_name', 'id');
        $data['ratingScaleModels'] = $this->ratingScale->findAll();

        if (isset($filter['emp_id'])) {
            $appraisals = $this->appraisal->getList($filter['emp_id']);
            $data['selected_employee'] = $this->employee->find($filter['emp_id']);
            $data['appraisalApprovalFlow'] = $this->employee->employeeAppraisalApprovalFlow($filter['emp_id']);
            $data['developmentPlan']=AppraisalDevelopmentPlan::where('appraisee',$filter['emp_id'])->first();
            // dd($data['development_plan']);
        }

        $final_report = [];
        if (!empty($appraisals)) {
            foreach ($appraisals as $key => $appraisal) {
                $final_report[$key]['overall_marks'] = number_format($appraisal->appraisalResponses->where('score', '!=', 0)->avg('score'), '2');
                $final_report[$key]['form'] = 'Part ' . $appraisal->questionnaire->form;

                if ($key == 0) {
                    $final_report[$key]['weightage'] = '70%';
                    $final_report[$key]['calculation_basis'] = 'Overall marks * 0.7';
                    $final_report[$key]['final_score'] = number_format($final_report[$key]['overall_marks'] * 0.7, 2);
                } else {
                    $final_report[$key]['weightage'] = '30%';
                    $final_report[$key]['calculation_basis'] = 'Overall marks * 0.3';
                    $final_report[$key]['final_score'] = number_format($final_report[$key]['overall_marks'] * 0.3, 2);
                }
            }
        }
        $data['final_reports'] = $final_report;

        return view('appraisal::performance-evaluation-summary.index', $data);
    }
}
