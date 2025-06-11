<?php
namespace App\Traits;
use App\Modules\User\Repositories\UserRepository;
use Jenssegers\Agent\Facades\Agent;
trait LogTrait{
    private $user;

    
    function setActivityLog($data){
        $this->user = new UserRepository(); 
        $browerDetails=[
            'plat_form'=>Agent::platForm(),
            'browser'=>Agent::browser(),
            'device'=>Agent::device(),
            'isDesktop'=>Agent::isDesktop(),
            'isTablet'=>Agent::isTablet(),
            'isMobile'=>Agent::isMobile(),
            'ipaddress'=>$_SERVER['REMOTE_ADDR']
        ];
        $activityLog = [
            'employee_id' => auth()->user()->emp_id ?? null,
            'type' => $data['title'],
            'date' => date('Y-m-d'),
            'nepali_date' => date_converter()->eng_to_nep_convert(date('Y-m-d')),
            'created_user_modal'=>get_class(auth()->user()),
            'created_user_id'=>auth()->user()->id,
            'browser_details'=>json_encode($browerDetails),
            'action_id'=>$data['action_id'],
            'action_model'=>$data['action_model'],
            'route'=>$data['route']
        ];
        $this->user->storeActivityLog($activityLog);
    }
}