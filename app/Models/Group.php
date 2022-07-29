<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Group extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'group';
    protected $guarded = ['id'];
    protected $fillable = [
        'kode',
        'nama',
        'keterangan',
        'dokumentasi',
        'setuju',
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasElement()
    {
        return $this->hasMany(Element::class, 'group_id', 'id');
    }

    public function getTotalElementAttribute()
    {
        $total = 0;

        if ($this->hasElement) {
            $total = $this->hasElement->count();
        }

        return $total;
    }

    public function getTotalSubElementAttribute()
    {
        $total = 0;

        if ($this->hasElement) {
            foreach ($this->hasElement as $element) {
                $total += $element->total_sub_element;
            }
        }

        return $total;
    }
}
