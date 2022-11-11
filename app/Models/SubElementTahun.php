<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubElementTahun extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'sub_element_tahun';
    protected $guarded = ['id'];
    protected $fillable = [
        'sub_element_id',
        'nilai',
        'legenda_id',
        'user_id',
    ];

    // legenda
    public function hasLegenda()
    {
        return $this->hasOne(Legenda::class, 'id', 'legenda_id');
    }
    // get nama legenda
    public function getLegendaAttribute()
    {
        if ($this->hasLegenda) {
            return $this->hasLegenda->nama;
        }
    }
}
