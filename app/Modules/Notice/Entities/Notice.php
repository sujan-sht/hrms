<?php

namespace App\Modules\Notice\Entities;

use App\Modules\Admin\Entities\File;
use App\Modules\Project\Entities\Project;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use SoftDeletes;
    const FILE_PATH = '/uploads/notice/';

    const TYPE = [
        '1' => 'Post Now',
        '2' => 'Schedule'
    ];

    protected $fillable = [
        'title',
        'description',
        'notice_date',
        'notice_date_nepali',
        'notice_time',
        'created_by',
        'project_id',
        'file',
        'type',
        'image',
        'organization_id',
        'department_id',
        'employee_id',
        'branch_id',
        'link'
    ];

    protected $hidden = ['deleted_at'];

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function departments()
    {
        return $this->hasMany(NoticeDepartment::class, 'notice_id');
    }

    public function getType()
    {
        return Notice::TYPE[$this->type ?? 1];
    }

    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });

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
