<?php

namespace App\Models;

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
}
