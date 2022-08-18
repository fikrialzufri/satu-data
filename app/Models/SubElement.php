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

    public function hasSubElementTahunAll()
    {
        return $this->hasMany(SubElementTahun::class, 'sub_element_id');
    }

    public function hasElement()
    {
        return $this->hasOne(Element::class, 'id', 'element_id');
    }

    public function hasSubElementTahun($tahun)
    {
        $data = $this->hasSubElementTahunAll()->where('tahun', $tahun)->first();
        $nilai = 0;
        if ($data) {
            $nilai = $data->nilai;
        }
        return $nilai;
    }
    public function hasSubElementLegend($tahun)
    {
        $data = $this->hasSubElementTahunAll()->where('tahun', $tahun)->first();
        $legenda_id = "";
        if ($data) {
            $legenda_id = $data->legenda_id;
        }
        return $legenda_id;
    }

    public function hasLegenda($tahun)
    {
        $data = $this->hasSubElementTahunAll()->where('tahun', $tahun)->first();
        $legenda = "";
        if ($data) {
            $legenda = $data->legenda;
        }
        return $legenda;
    }

    public function getElementNilaiAttribute()
    {
        if ($this->hasSubElementTahun) {
            return $this->hasSubElementTahun;
        }
    }

    public function getElementKodeHasilAttribute()
    {
        if ($this->hasElement) {
            return $this->hasElement->kode_hasil;
        }
    }
    public function getGroupAttribute()
    {
        if ($this->hasElement) {
            return $this->hasElement->group;
        }
    }
    public function getJenisAttribute()
    {
        if ($this->hasElement) {
            return $this->hasElement->jenis_data;
        }
    }
    public function getUnitAttribute()
    {
        if ($this->hasElement) {
            return $this->hasElement->unit;
        }
    }
    public function getKeteranganElementAttribute()
    {
        if ($this->hasElement) {
            return $this->hasElement->keterangan;
        }
    }

    public function getKodeHasilAttribute()
    {
        return $this->element_kode_hasil . "." . $this->kode;
    }

    public function getTotalAttribute()
    {
        return $this->hasSubElementTahunAll()->count();
    }
}
