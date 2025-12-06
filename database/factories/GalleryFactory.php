<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    public function definition(): array
    {
        $route = 'gallery';
        
        if (!Storage::disk('public')->exists($route)) {
            Storage::disk('public')->makeDirectory($route);
        }
        if (!Storage::disk('public')->exists($route . '/thumbnail')) {
            Storage::disk('public')->makeDirectory($route . '/thumbnail');
        }

        $nama_gambar = Str::slug($route) . '-' . Str::random(15) . '.jpg';
        
        $width = 800;
        $height = 600;
        $image = Image::canvas($width, $height, $this->faker->hexColor());
        
        for ($i = 0; $i < 5; $i++) {
            $image->circle(rand(50, 200), rand(0, $width), rand(0, $height), function ($draw) {
                $draw->background($this->faker->hexColor());
                $draw->border(2, $this->faker->hexColor());
            });
        }
        
        $path = storage_path('app/public/' . $route . '/' . $nama_gambar);
        $image->save($path);
        
        $thumbnail = Image::make($path)->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thumbnailPath = storage_path('app/public/' . $route . '/thumbnail/' . $nama_gambar);
        $thumbnail->save($thumbnailPath);

        return [
            'nama' => $this->faker->words(3, true),
            'gambar' => $nama_gambar,
            'deskripsi' => $this->faker->paragraph(),
        ];
    }
}
