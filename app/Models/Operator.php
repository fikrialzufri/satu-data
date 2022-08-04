<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Operator extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'operator';
    protected $guarded = ['id'];
    protected $fillable = [
        'nama',
        'unit_id',
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
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
    public function hasUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getUsernameAttribute()
    {
        if ($this->hasUser) {
            return $this->hasUser->username;
        }
    }
    public function getEmailAttribute()
    {
        if ($this->hasUser) {
            return $this->hasUser->email;
        }
    }
}
