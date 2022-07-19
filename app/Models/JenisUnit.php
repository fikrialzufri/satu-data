<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class JenisUnit extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'jenis_unit';
    protected $guarded = ['id'];
    protected $fillable = [
        'nama',
        'warna',
        'keterangan',
        'setuju',
        'tampil',
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasUnit()
    {
        return $this->hasMany(Unit::class, 'jenis_unit_id');
    }

    public function getTotalUnitAttribute()
    {
        $total = "";
        if ($this->hasUnit) {

            $total = $this->hasUnit()->count() . " Unit Terkait";
        }

        return $total;
    }
}
