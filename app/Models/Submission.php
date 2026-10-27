<?php

namespace App\Models;

use App\Models\User;
use App\Models\Status;
use App\Models\Approval;
use App\Models\FileType;
use App\Models\AreaKerja;
use App\Models\UnitKerja;
use App\Models\FileUpload;
use App\Models\UserApproval;
use App\Models\DataSubmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submission extends Model
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = [
        'unit_kerja',
        'status_id',
        'user_id',
        'contact_person',
        'nama',
        'npk',
        'role_id',
        'keterangan_approval',
        'verification_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function user()
    {
        return $this->belongsTo(UserApproval::class);
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'submission_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function dataSubmission()
    {
        return $this->hasMany(DataSubmission::class);
    }

    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class);
    }

    public function approve()
    {
        $this->status_id = 2;
        $this->save();
    }

    public function reject()
    {
        $this->status_id = 3;
        $this->save();
    }
}
