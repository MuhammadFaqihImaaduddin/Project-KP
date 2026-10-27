<?php

namespace App\Models;

use App\Models\UnitKerja;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AreaKerja extends Model
{
    use HasFactory;

    protected $table = 'area_kerja';

    protected $fillable = [
        'nama',
    ];

    public function unitKerjas()
    {
        return $this->hasMany(UnitKerja::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'area_kerja_id');
    }
}
