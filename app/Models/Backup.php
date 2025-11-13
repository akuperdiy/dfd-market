<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['filename'];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}

