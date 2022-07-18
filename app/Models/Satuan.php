<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Satuan extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'satuan';
    protected $guarded = ['id'];
    protected $fillable = [
        'nama',
        'warna'
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
