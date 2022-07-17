<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Legenda extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'legenda';
    protected $guarded = ['id'];
    protected $fillable = [
        'nama',
        'warna',
        'keterangan',
        'setuju'
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
