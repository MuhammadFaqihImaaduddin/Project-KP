<?php

namespace App\Models;

use App\Models\Submission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public static function getStatusByName($name)
    {
        return static::where('name', $name)->first();
    }

}
