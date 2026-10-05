<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class Media extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'media';
    protected $guarded = ['id'];
    protected $fillable = [
        'nama',
        'module',
        'file',
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
