<?php

namespace App\Modules\Onboarding\Repositories;

use App\Modules\Setting\Entities\Setting;
use App\Modules\Admin\Entities\MailSender;
use App\Modules\Onboarding\Entities\Applicant;
use App\Modules\Onboarding\Entities\Interview;

class InterviewRepository implements InterviewInterface
{
    public function getList()
    {
        return Interview::pluck('first_name', 'id');
    }

    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = Interview::when(array_keys($filter, true), function ($query) use ($filter) {
            if (isset($filter['organization']) && !empty($filter['organization'])) {
                $query->whereHas('applicantModel.mrfModel',function($query){
                $query->where('organization_id', optional(auth()->user()->userEmployer)->organization_id);
            });
            }
            if(setting('calendar_type') == 'BS'){
                if (isset($filter['date']) && !empty($filter['date'])) {
                   $query->where('date', date_converter()->nep_to_eng_convert($filter['date']));
                }
            }else{
                if (isset($filter['date']) && !empty($filter['date'])) {
                    $query->where('date', $filter['date']);
                }
            }
            if (isset($filter['level']) && !empty($filter['level'])) {
                $query->where('interview_level_id', $filter['level']);
            }
            if (isset($filter['applicant']) && !empty($filter['applicant'])) {
                $query->where('applicant_id', $filter['applicant']);
            }
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 99999));

        return $result;
    }

    public function findOne($id)
    {
        return Interview::find($id);
    }

    public function create($data)
    {
        return Interview::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->findOne($id);

        return $result->update($data);
    }

    public function delete($id)
    {
        return Interview::destroy($id);
    }

    public function sendMailNotification($model)
    {
        $appicantModel = Applicant::find($model->applicant_id);
        $mrfModel = $appicantModel->mrfModel;
        $mailArray = [];

        // send email to applicant
        if (isset($appicantModel->email)) {
            $notified_user_email = $appicantModel->email;
            if (isset($notified_user_email) && !empty($notified_user_email)) {
                $notified_user_fullname = $appicantModel->full_name;
                $mailArray[] = array(
                    'email' => $notified_user_email,
                    'subject' => 'Interview Notification',
                    'notified_user_fullname' => $notified_user_fullname,
                    'interviewModel' => $model,
                    'mrfModel' => $mrfModel,
                    'setting' => Setting::first()
                );
            }
        }
        // Send all email at once
        if (count($mailArray) > 0) {
            foreach ($mailArray as $mailDetail) {
                $mail = new MailSender();
                $mail->sendMail('admin::mail.interview', $mailDetail);
            }
        }

        return true;
    }
}
