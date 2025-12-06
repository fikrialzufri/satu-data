<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    use CrudTrait;

    public function model()
    {
        return new Gallery();
    }

    public function __construct()
    {
        $this->route = 'gallery';
        $this->title = 'Gallery';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'nama',
                'alias'    => 'Nama Gallery',
            ],
            [
                'name'    => 'gambar',
                'input'    => 'image',
                'alias'    => 'Gambar',
            ],
            [
                'name'    => 'deskripsi',
                'alias'    => 'Deskripsi',
            ],
        ];
    }

    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Gallery',
                'value'    => null
            ],
        ];
    }

    public function configForm()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Gallery',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'gambar',
                'input'    => 'image',
                'alias'    => 'Gambar',
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'deskripsi',
                'input'    => 'textarea',
                'alias'    => 'Deskripsi',
                'validasi'    => [],
            ],
        ];
    }

    public function apiIndex(Request $request)
    {
        $perPage = $request->get('per_page', 12);
        $search = $request->get('search', '');
        $ids = $request->get('ids', '');
        
        $query = Gallery::orderBy('nama');
        
        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }
        
        if ($ids) {
            $idArray = explode(',', $ids);
            $query->whereIn('id', $idArray);
        }
        
        $galleries = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'nama' => $gallery->nama,
                    'gambar' => $gallery->gambar ? asset('storage/gallery/thumbnail/' . $gallery->gambar) : asset('img/default-icon.png'),
                    'gambar_path' => $gallery->gambar,
                    'deskripsi' => $gallery->deskripsi,
                ];
            }),
            'pagination' => [
                'current_page' => $galleries->currentPage(),
                'last_page' => $galleries->lastPage(),
                'per_page' => $galleries->perPage(),
                'total' => $galleries->total(),
                'from' => $galleries->firstItem(),
                'to' => $galleries->lastItem(),
            ]
        ]);
    }

    public function apiUpload(Request $request)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'nama' => 'nullable|string|max:255',
        ]);

        $route = 'gallery';
        $file = $request->file('gambar');
        $nama_gambar = Str::slug($route) . '-' . Str::random(15) . '.' . $file->getClientOriginalExtension();

        if (!Storage::disk('public')->exists($route)) {
            Storage::disk('public')->makeDirectory($route);
        }
        if (!Storage::disk('public')->exists($route . '/thumbnail')) {
            Storage::disk('public')->makeDirectory($route . '/thumbnail');
        }

        $gambar_original = Image::make($file);
        Storage::disk('public')->put($route . '/' . $nama_gambar, $gambar_original->stream());

        $thumbnail = Image::make($file)->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        Storage::disk('public')->put($route . '/thumbnail/' . $nama_gambar, $thumbnail->stream());

        $gallery = Gallery::create([
            'nama' => $request->input('nama', $file->getClientOriginalName()),
            'gambar' => $nama_gambar,
            'deskripsi' => $request->input('deskripsi', ''),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $gallery->id,
                'nama' => $gallery->nama,
                'gambar' => asset('storage/gallery/thumbnail/' . $gallery->gambar),
                'gambar_path' => $gallery->gambar,
                'deskripsi' => $gallery->deskripsi,
            ],
            'message' => 'Gambar berhasil diupload'
        ]);
    }
}
