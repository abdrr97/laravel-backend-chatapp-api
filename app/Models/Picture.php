<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'extention',
        'name',
        'user_id',
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
