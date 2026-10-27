<?php

namespace App\Models;

use App\Models\Submission;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = ['submission_id', 'approver_id', 'status_id'];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
