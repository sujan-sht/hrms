<?php

namespace App\Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const FILE_PATH = '/uploads/setting/';
    const SHOW_PATH = 'uploads/setting/';

    protected $fillable = [

        'company_name',
        'company_logo',
        'website',
        'company_email',
        'company_info',
        'enable_mail',
        'leave_deduction_from_biometric',

        'contact_no1',
        'contact_no2',
        'address1',
        'address2',
        'fax',
        'post_box',
        'google_map',
        'sync_host_name',
        'sync_organization',
        'flag_organization',
        'sync_employee',
        'flag_employee',

        'facebook_link',
        'instagram_link',
        'linkin_link',
        'twitter_link',
        'youtube_link',
        'calendar_type',
        'web_attendance',
        'play_store_app_version',
        'apple_store_app_version',
        'force_app_update',
        'app_update_description',
        'real_time_app_atd',
        'two_step_substitute_leave',
        'attendance_lock'
    ];

    public function getFileFullPathAttribute()
    {
        return self::FILE_PATH . $this->file_name;
    }

    public function getLogo()
    {
        if(isset($this->company_logo)) {
            return asset(Self::SHOW_PATH.$this->company_logo);
        } else {
            return asset('admin/bidhee_logo.png');
        }
    }

    /**
     *
     */
    public function getData()
    {
        return Setting::first();
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' . $model);
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }
}
