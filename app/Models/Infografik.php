<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Infografik extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'infografik';

    protected $fillable = [
        'judul',
        'slug',
        'kategori_infografik_id',
        'thumbnail_id',
        'isi_infografik',
        'viewer',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($infografik) {
            if (auth()->check()) {
                $infografik->created_by = auth()->id();
                $infografik->updated_by = auth()->id();
            }
        });

        static::updating(function ($infografik) {
            if (auth()->check()) {
                $infografik->updated_by = auth()->id();
            }
        });
    }

    public function setJudulAttribute($value)
    {
        $this->attributes['judul'] = $value;
        // jika judul sama maka slug +
        if (Infografik::where('slug', Str::slug($value))->exists()) {
            $this->attributes['slug'] = Str::slug($value) . '-' . Infografik::where('slug', Str::slug($value))->count() + 1;
        } else {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function kategoriInfografik()
    {
        return $this->belongsTo(KategoriInfografik::class, 'kategori_infografik_id');
    }

    public function hasKategoriInfografik()
    {
        return $this->hasOne(KategoriInfografik::class, 'id', 'kategori_infografik_id');
    }

    public function getKategoriInfografikAttribute()
    {
        if ($this->hasKategoriInfografik) {
            return $this->hasKategoriInfografik->nama;
        }
    }

    public function thumbnail()
    {
        return $this->belongsTo(Gallery::class, 'thumbnail_id');
    }

    public function hasThumbnail()
    {
        return $this->hasOne(Gallery::class, 'id', 'thumbnail_id');
    }

    public function getThumbnailAttribute()
    {
        if ($this->hasThumbnail) {
            return $this->hasThumbnail->gambar;
        }
    }

    public function hasGallery()
    {
        return $this->belongsToMany(Gallery::class, 'gallery_infografik', 'infografik_id', 'gallery_id')->withTimestamps();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
