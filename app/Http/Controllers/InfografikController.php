<?php

namespace App\Http\Controllers;

use App\Models\Infografik;
use App\Models\KategoriInfografik;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class InfografikController extends Controller
{
    use CrudTrait;

    public function model()
    {
        return new Infografik();
    }

    public function __construct()
    {
        $this->route = 'infografik';
        $this->title = 'Infografik';
        $this->manyToMany = ['gallery'];
        $this->relations = ['kategoriInfografik'];
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'judul',
                'alias'    => 'Judul',
            ],
            [
                'name'    => 'kategori_infografik',
                'alias'    => 'Kategori',
            ],
            [
                'name'    => 'thumbnail',
                'input'    => 'image',
                'alias'    => 'Thumbnail',
            ],
        ];
    }

    public function configSearch()
    {
        return [
            [
                'name'    => 'judul',
                'input'    => 'text',
                'alias'    => 'Judul',
                'value'    => null
            ],
            [
                'name'    => 'kategori_infografik_id',
                'input'    => 'combo',
                'alias'    => 'Kategori Infografik',
                'value'    => $this->combobox('KategoriInfografik'),
            ],
        ];
    }

    public function configForm()
    {
        return [
            [
                'name'    => 'judul',
                'input'    => 'text',
                'alias'    => 'Judul',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'kategori_infografik_id',
                'input'    => 'combo',
                'alias'    => 'Kategori Infografik',
                'value'    => $this->combobox('KategoriInfografik'),
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'thumbnail_id',
                'input'    => 'gallery-modal',
                'alias'    => 'Thumbnail',
                'validasi'    => ['required'],
                'multiple'    => false,
            ],
            [
                'name'    => 'isi_infografik',
                'input'    => 'textarea',
                'alias'    => 'Isi Infografik',
                'validasi'    => [],
            ],
            [
                'name'    => 'gallery',
                'input'    => 'gallery-modal',
                'alias'    => 'Gallery',
                'validasi'    => [],
                'multiple'    => true,
            ],
        ];
    }

    public function edit($id)
    {
        $data = $this->model()->find($id);
        if (!isset($this->title)) {
            $title = "Ubah " . ucwords(str_replace('-', ' ', $this->route)) . ' - : ' . $data->judul;
        } else {
            $title = "Ubah " . ucwords(str_replace('-', ' ', $this->title)) . ' - : ' . $data->judul;
        }

        if (isset($this->manyToMany) && !isset($this->extraFrom)) {
            foreach ($this->manyToMany as $value) {
                $hasRelation = 'has' . ucfirst($value);
                $galleryIds = $data->$hasRelation()->pluck('id')->toArray();
                $data->$value = $galleryIds;
            }
        }

        if ($data->thumbnail_id) {
            $data->thumbnail_id = $data->thumbnail_id;
        }

        $route = $this->route;
        $store = "update";

        $form = $this->configform();
        $count = count($form);

        $colomField = $this->colomField($count);

        $countColom = $this->countColom($count);
        $countColomFooter = $this->countColomFooter($count);

        return view(
            'template.form',
            compact(
                'route',
                'store',
                'colomField',
                'countColom',
                'countColomFooter',
                'title',
                'form',
                'data'
            )
        );
    }

    public function index()
    {
        $title = $this->title ?? ucwords(str_replace('-', ' ', $this->route));
        $route = $this->route;

        $query = $this->model()->with(['kategoriInfografik', 'thumbnail']);

        $search = request()->get('judul', '');
        $kategoriId = request()->get('kategori_infografik_id', '');
        $sort = request()->get('sort', 'terbaru');

        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%');
        }

        if ($kategoriId) {
            $query->where('kategori_infografik_id', $kategoriId);
        }

        match ($sort) {
            'terbaru' => $query->orderBy('created_at', 'desc'),
            'terlama' => $query->orderBy('created_at', 'asc'),
            'a-z' => $query->orderBy('judul', 'asc'),
            'z-a' => $query->orderBy('judul', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $data = $query->paginate(12);
        $total = $data->total();

        $kategoriOptions = $this->combobox('KategoriInfografik');

        return view('infografik.index', compact(
            'title',
            'route',
            'data',
            'total',
            'search',
            'kategoriId',
            'sort',
            'kategoriOptions'
        ));
    }

    public function detail($slug)
    {
        $infografik = $this->model()->where('slug', $slug)
            ->with(['kategoriInfografik', 'thumbnail', 'hasGallery'])
            ->firstOrFail();

        $infografik->increment('viewer');

        $kategoriInfografik = $infografik->kategoriInfografik()->first();
        $title = $infografik->judul;

        $relatedInfografik = $this->model()
            ->where('id', '!=', $infografik->id)
            ->with(['kategoriInfografik', 'thumbnail'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('infografik.detail', compact('infografik', 'kategoriInfografik', 'title', 'relatedInfografik'));
    }

    public function sendckan($id)
    {
        $infografik = $this->model()->with(['kategoriInfografik', 'thumbnail', 'hasGallery'])->find($id);

        if (!$infografik) {
            return redirect()->route('infografik.index')->with('message', 'Infografik tidak ditemukan')->with('Class', 'danger')->with('icon', 'error');
        }

        $thumbnailUrl = '';
        if ($infografik->thumbnail) {
            $thumbnailUrl = asset('storage/gallery/' . $infografik->thumbnail);
        }

        $gallery = [];
        if ($infografik->hasGallery && $infografik->hasGallery->count() > 0) {
            foreach ($infografik->hasGallery as $galleryItem) {
                $gallery[] = [
                    'linkurl' => asset('storage/gallery/' . $galleryItem->gambar)
                ];
            }
        }

        $kategori = $infografik->kategoriInfografik ? $infografik->kategoriInfografik->nama : 'Umum';
        $urlSismut = route('infografik.detail', $infografik->slug);

        $apiURL = env('CKAN_ENDPOINT') . '/api/action/package_create';

        $postInput = [
            'Judul' => $infografik->judul,
            'Isi' => $infografik->isi_infografik ?? '',
            'slug' => $infografik->slug,
            'thumbnai' => $thumbnailUrl,
            'gallery' => $gallery,
            'Kategori' => $kategori,
            'DisusunOleh' => 'Jabar Digital Service',
            'Viewer' => $infografik->viewer ?? 0,
            'url_sismut' => $urlSismut,
        ];

        $headers = [
            'Authorization' => env('CKAN_AUTH'),
            'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $postInput);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        if (isset($responseBody['success']) && $responseBody['success'] == false) {
            return redirect()->route('infografik.detail', $infografik->slug)->with('message', 'Infografik sudah ada di CKAN')->with('Class', 'danger')->with('icon', 'error');
        }

        return redirect()->route('infografik.detail', $infografik->slug)->with('message', 'Infografik berhasil dikirim ke CKAN')->with('Class', 'success')->with('icon', 'success');
    }
}
