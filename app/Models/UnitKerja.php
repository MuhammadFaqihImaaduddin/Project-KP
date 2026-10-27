<?php

namespace App\Models;

use App\Models\AreaKerja;
use App\Models\Submission;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitKerja extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'unit_kerja';

    // Specify the primary key column
    protected $primaryKey = 'id';

    // Other model properties and methods...

    protected $fillable = [
        'area_kerja_id', 'nama', 'kode'
    ];

    public function areaKerja()
    {
        return $this->belongsTo(AreaKerja::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'unit_kerja');
    }

        public function userPemohon()
    {
        // Asumsi: user Pemohon memiliki role_id 4
        return $this->hasOne(User::class, 'unit_kerja_id')->where('role_id', 4);
    }
}
