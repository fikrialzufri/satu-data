<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
use Auth;

class Group extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

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
        if (Auth::user()) {
            $unit_id =  Auth::user()->id_unit;
            $checkElement = Element::where('unit_id', $unit_id)->pluck('id')->toArray();

            if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
                return $this->hasMany(Element::class, 'group_id', 'id');
            } else {
                return $this->hasMany(Element::class, 'group_id', 'id')->whereIn('id', $checkElement);
            }
        } else {
            return $this->hasMany(Element::class, 'group_id', 'id');
        }
    }
    public function hasElementJenis($jenis)
    {
        $total = 0;
        if ($this->hasElement()) {
            $dataelements =  $this->hasElement()->where('jenis_data_id', $jenis)->get();
            foreach ($dataelements as $element) {
                $total += $element->total_sub_element;
            }
        }
        return $total;
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

    public function hasSubElement($tahun)
    {

        $total = 0;
        if ($this->hasElement) {
            foreach ($this->hasElement as $element) {

                foreach ($element->hasSubElement as $subElement) {
                    $total += $subElement->hasSubElementTahun($tahun);
                }
            }
        }

        return $total;
    }
}
