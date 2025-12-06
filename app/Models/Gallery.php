<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'galleries';

    protected $fillable = [
        'nama',
        'gambar',
        'deskripsi',
    ];
}

