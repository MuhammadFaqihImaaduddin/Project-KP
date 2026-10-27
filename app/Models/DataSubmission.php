<?php

namespace App\Models;

use App\Models\FileUpload;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataSubmission extends Model
{
    use HasFactory;
    protected $table = 'data_submission';

    protected $fillable = [
        'submission_id', 'role_id', 'nama_tamu', 'status_id'
    ];


    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }

    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class, 'data_submission_id');
    }


// Perbaikan semua relasi spesifik file
    public function ktpFile()
    {
        return $this->hasOne(FileUpload::class, 'data_submission_id')->where('tipe', 'ktp');
    }

    public function boosterFile()
    {
        return $this->hasOne(FileUpload::class, 'data_submission_id')->where('tipe', 'booster');
    }

    public function safetyInductionFile()
    {
        return $this->hasOne(FileUpload::class, 'data_submission_id')->where('tipe', 'induction');
    }

    public function pdpeFile()
    {
        return $this->hasOne(FileUpload::class, 'data_submission_id')->where('tipe', 'pdpe');
    }


    public function approve()
    {
        // Lakukan logika persetujuan di sini
        $this->status_id = 2; // Misalnya, mengubah status_id menjadi 2 untuk menunjukkan disetujui
        $this->save();
    }

    public function reject()
    {
        // Lakukan logika penolakan di sini
        $this->status_id = 3; // Misalnya, mengubah status_id menjadi 3 untuk menunjukkan ditolak
        $this->save();
    }


}
