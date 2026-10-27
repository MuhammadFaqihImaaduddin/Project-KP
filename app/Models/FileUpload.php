<?php

namespace App\Models;

use App\Models\FileType;
use App\Models\FileGroup;
use App\Models\Submission;
use App\Models\DataSubmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileUpload extends Model
{
    use HasFactory;

    protected $table = 'file_uploads';

    protected $fillable = [
        'data_submission_id',
        'file_name',
        'file_path',
        'tipe',
    ];

    public function dataSubmission()
    {
        return $this->belongsTo(DataSubmission::class);
    }

}
