<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class Notifikasi extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'notifikasi';
    protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'body',
        'modul',
        'status',
        'from_user_id',
        'to_user_id',
        'modul_id'
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // public function hasAduan()
    // {
    //     return $this->hasOne(Aduan::class, 'id', 'modul_id');
    // }
}
