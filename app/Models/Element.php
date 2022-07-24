<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Element extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'element';
    protected $guarded = ['id'];
    protected $fillable = [
        'nama',
        'nama',
        'keterangan',
        'dokumentasi',
        'setuju',
        'group_id',
        'unit_id',
        'jenis_data_id',
        'user_id',
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasJenisData()
    {
        return $this->hasOne(JenisData::class, 'id', 'jenis_data_id');
    }

    public function getJenisDataAttribute()
    {
        if ($this->hasJenisData) {
            return $this->hasJenisData->nama;
        }
    }
    public function hasGroup()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }

    public function getGroupAttribute()
    {
        if ($this->hasGroup) {
            return $this->hasGroup->nama;
        }
    }
    public function hasUnit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function getUnitAttribute()
    {
        if ($this->hasUnit) {
            return $this->hasUnit->nama;
        }
    }

    public function hasSubElement()
    {
        return $this->hasMany(SubElement::class, 'element_id');
    }

    public function getTotalSubElementAttribute()
    {
        $total = "";
        if ($this->hasSubElement) {

            $total = $this->hasSubElement()->count();
        }

        return $total;
    }
}
