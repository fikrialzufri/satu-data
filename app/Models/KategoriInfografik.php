<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class KategoriInfografik extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'kategori_infografik';

    protected $fillable = [
        'nama',
        'slug',
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function infografik()
    {
        return $this->hasMany(Infografik::class, 'kategori_infografik_id');
    }
}
