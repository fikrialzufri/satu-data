<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class JenisData extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'jenis_data';
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

    public function hasElement()
    {
        return $this->hasMany(Element::class, 'jenis_data_id', 'id');
    }

    public function getTotalElementAttribute()
    {
        $total = 0;
        if ($this->hasElement) {

            $total = $this->hasElement()->count();
        }

        return $total;
    }
}
