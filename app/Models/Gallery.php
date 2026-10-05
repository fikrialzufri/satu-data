<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'galleries';

    protected $fillable = [
        'nama',
        'gambar',
        'deskripsi',
    ];
}

