<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['file_field_1', 'file_field_2'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
