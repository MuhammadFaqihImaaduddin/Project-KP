<?php

namespace App\Models;
use App\Models\Role;
use App\Models\Approval;
use App\Models\AreaKerja;
use App\Models\UnitKerja;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'role',
        'unit_kerja_id',
        'area_kerja_id',
    ];

    public static $rules = [
        'area_kerja_id' => 'required',
        // ... other rules
    ];

    protected $hidden = [
        'password',
    ];

    protected $table = 'users';

    protected $rememberTokenName = null;

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'approver_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'user_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja');
    }

    public function areaKerja()
    {
        return $this->belongsTo(AreaKerja::class, 'area_kerja_id');
    }
}
