<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name']; // Sesuaikan atribut yang ingin disimpan

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function dataSubmissions()
    {
        return $this->hasMany(DataSubmission::class);
    }
}
