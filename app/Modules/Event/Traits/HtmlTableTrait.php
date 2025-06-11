<?php 

namespace App\Modules\Event\Traits;

trait HtmlTableTrait
{
    public function get_view_event_html($event)
    {
        $event_users = '';
        //$users = [] ;
        $html_users = '';
        if(!empty($event->tagged_employees)) {
            $tagged_users = json_decode($event->tagged_employees); 
            foreach($tagged_users as $user_id) {
                //$users[] = \App\Modules\User\Repositories\UserRepository::getName($user_id);
                $username = \App\Modules\User\Repositories\UserRepository::getName($user_id);

                $html_users .= '<li>'.$username.'</li>';
            }
            //$event_users = implode(', ', $users);   
                 
        }
        



        $html ='';
        $html .='<div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">'.$event->title.'</h5>
                <div class="modal-events-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="modal-body">
            <div class="events-info">
                <i class="fa fa-clock"></i>
                <div class="events-info_date">
                    <p>'.date('l, M d', strtotime($event->event_date)).'</p>
                    <span>'.date('g:i A', strtotime($event->event_time)).'</span>
                </div>
            </div>
            <div class="events-info">
                <i class="fa fa-map-marker"></i>
                <div class="events-info_date">
                    <p>'.$event->location.'</p>
                </div>
            </div>
            <div class="events-info">
                <i class="fa fa-users"></i>
                <div class="events-info_date">
                <ul>'.$html_users.'</ul>
                </div>
            </div>
            <div class="events-info">
                <i class="fa fa-user"></i>
                <div class="events-info_date">
                <p><b>Created By:</b> '.optional($event->createdBy)->first_name.' '. optional($event->createdBy)->last_name.'</p>
                </div>
            </div>
            <div class="events-info">
                <i class="fa fa-bookmark"></i>
                <div class="events-info_date">
                    <p><b>Note:</b> '.$event->note.'</p>
                </div>
            </div>
            <div class="events-info">
                <i class="fa fa-bars"></i>
                <div class="events-info_date">
                    <p>'.$event->description.'</p>
                </div>
            </div>
            </div>';

        return  $html;
    }

    public function get_view_holiday_html($holiday)
    {
        $html ='';
        $html .='<div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">'.$holiday->title.'</h5>
                <div class="modal-events-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="modal-body">
            <div class="events-info">
                <i class="fa fa-clock"></i>
                <div class="events-info_date">
                    <p>'.date('l, M d', strtotime($holiday->event_date)).'</p>
                </div>
            </div>
            <div class="events-info">
                <i class="fa fa-users"></i>
                <div class="events-info_date">
                Holiday For : '.($holiday->type == 0 ? 'All' : ($holiday->type_value == 1 ? 'Male Only' : 'Female Only')).'
                </div>
            </div>
            <div class="events-info">
                <i class="fa fa-user"></i>
                <div class="events-info_date">
                <p><b>Created By:</b> '.optional($holiday->createdBy)->first_name.' '. optional($holiday->createdBy)->last_name.'</p>
                </div>
            </div>
            </div>';

        return  $html;
    }

}