<?php

namespace App\Modules\Document\Entities;

use Illuminate\Database\Eloquent\Model;

class DocumentAttachment extends Model
{
    const FILE_PATH = 'uploads/document/attachment/';

    protected $fillable = [
        'document_id',
        'title',
        'extension',
        'size'
    ];

    /**
     *
     */
    public function getAttachmentAttribute()
    {
        return asset(Self::FILE_PATH . $this->title);
    }

    /**
     *
     */
    public function getSize()
    {
        $bytes = $this->size;

        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
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
}
