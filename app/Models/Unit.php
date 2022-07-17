<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'unit';
    protected $guarded = ['id'];
    protected $fillable = [
        'nama',
        'nama_singkat',
        'latitude',
        'longitude',
        'setuju',
        'tampil',
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
