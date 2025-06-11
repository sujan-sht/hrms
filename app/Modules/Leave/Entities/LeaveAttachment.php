<?php

namespace App\Modules\Leave\Entities;

use Illuminate\Database\Eloquent\Model;

class LeaveAttachment extends Model
{
    const FILE_PATH = 'uploads/leave/attachment/';

    protected $fillable = [
        'leave_id',
        'title',
        'extension',
        'size'
    ];

    /**
     * Relation with leave
     */
    public function leave() {
        return $this->belongsTo(Leave::class, 'leave_id');
    }

    /**
     *
     */
    public function getAttachmentAttribute() {
        return asset(Self::FILE_PATH . $this->title);
    }

    /**
     *
     */
    public function getSize()
    {
        $bytes = $this->size;

        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    /**
     * save file
     */
    public static function saveFile($file)
    {
        $imageName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize(); // in bytes

        $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        $file->move(public_path() . '/' . Self::FILE_PATH, $fileName);

        return [
            'filename' => $fileName,
            'extension' => $extension,
            'size' => $size
        ];
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
