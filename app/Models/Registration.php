<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'unitKerja', 'status'];

    public function files()
    {
        return $this->hasOne(File::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
