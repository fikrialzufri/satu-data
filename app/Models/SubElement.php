<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class SubElement extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'sub_element';
    protected $guarded = ['id'];
    protected $fillable = [
        'nama',
        'nilai',
        'latitude',
        'keterangan',
        'sumber_data',
        'metode_perhitungan',
        'meta_data',
        'satuan_id',
        'element_id',
        'user_id',
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasSatuan()
    {
        return $this->hasOne(Satuan::class, 'id', 'satuan_id');
    }

    public function getSatuanAttribute()
    {
        if ($this->hasSatuan) {
            return $this->hasSatuan->nama;
        }
    }
}
