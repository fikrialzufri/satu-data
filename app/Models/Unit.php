<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

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

    public function hasJenisUnit()
    {
        return $this->hasOne(JenisUnit::class, 'id', 'jenis_unit_id');
    }

    public function getJenisUnitAttribute()
    {
        if ($this->hasJenisUnit) {
            return $this->hasJenisUnit->nama;
        }
    }
    public function getJenisUnitWarnaAttribute()
    {
        if ($this->hasJenisUnit) {
            return $this->hasJenisUnit->warna;
        }
    }

    public function hasUser()
    {
        return $this->hasOne(User::class, 'id');
    }

    public function getUsernameAttribute()
    {
        if ($this->hasUser) {
            return $this->hasUser->username;
        }
    }

    public function hasElement()
    {
        return $this->hasMany(Element::class, 'unit_id');
    }

    public function getTotalElementAttribute()
    {
        $total = "";
        if ($this->hasElement) {

            $total = $this->hasElement()->count();
        }

        return $total;
    }
}
