<?php

namespace App\Http\Controllers;

use App\Models\JenisUnit;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Str;
use Storage;
use DB;
use Auth;
use Intervention\Image\Facades\Image;

class UnitController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'unit';
        $this->index = 'unit';
        $this->middleware('permission:view-' . $this->route, ['only' => ['show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [

            [
                'name'    => 'tampil',
                'alias'    => 'Tampil',
            ],
            [
                'name'    => 'akun',
                'alias'    => 'Akun',
            ],
            [
                'name'    => 'nama_singkat',
                'alias'    => 'Nama Unit',
            ],
            [
                'name'    => 'jenis_unit',
                'alias'    => 'Jenis',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Unit',
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
                'alias'    => 'Nama Unit',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'warna',
                'input'    => 'warna',
                'alias'    => 'Warna',
                'default'    => '#006838',
                'validasi'    => ['required'],
            ],
        ];
    }

    public function index()
    {
        //nama title
        if (!isset($this->title)) {
            $title =  ucwords($this->route);
        } else {
            $title =  ucwords($this->title);
        }

        //nama route
        $route =  $this->route;

        //nama relation
        $relations =  $this->relations;

        //nama jumlah pagination
        $paginate =  $this->paginate;

        //declare nilai serch pertama
        $search = null;

        //memanggil configHeaders
        $configHeaders = $this->configHeaders();

        //memangil model peratama
        $query = $this->model()::query();

        //button
        $button = null;

        //tambah data
        $tambah = $this->tambah;

        //tambah data
        $upload = $this->upload;

        $export = null;

        if ($this->configButton()) {
            $button = $this->configButton();
        }
        //mulai pencarian --------------------------------
        $searches = $this->configSearch();
        $searchValues = [];
        $n = 0;
        $countAll = 0;
        $queryArray = [];
        $queryRaw = '';
        foreach ($searches as $key => $val) {
            $search[$key] = request()->input($val['name']);
            $hasilSearch[$val['name']] = $search[$key];

            if ($search[$key]) {
                if ($val['input'] != 'daterange') {
                    # code...
                    $searchValues[$key] = preg_split('/\s+/', $search[$key], -1, PREG_SPLIT_NO_EMPTY);

                    if (count($searchValues[$key]) == 1) {
                        foreach ($searchValues[$key] as $index => $value) {
                            $query->where($val['name'], 'like', "%{$value}%");
                            $countAll = $countAll + 1;
                        }
                    } else {
                        $lastquery = '';

                        foreach ($searchValues[$key] as $index => $word) {
                            if (preg_match("/^[a-zA-Z0-9]+$/", $word) == 1) {

                                if ($queryRaw) {
                                    $count =  $this->model()->whereRaw(rtrim($queryRaw, " and"))->count();
                                    if ($count > 0) {
                                        $countAll = $countAll + 1;
                                        $lastquery = $queryRaw;

                                        $queryRaw .= $val['name'] . ' LIKE "%' . $word . '%" and ';
                                        if ($this->model()->whereRaw(rtrim($queryRaw, " and"))->count() == 0) {
                                            $queryRaw = $lastquery;
                                        }
                                    }
                                } else {
                                    $count =  $this->model()->where($val['name'], 'like', "%{$word}%")->count();
                                    if ($count > 0) {
                                        $countAll = $countAll + 1;

                                        $queryRaw .= $val['name'] . ' LIKE "%' . $word . '%" and ';
                                        continue;
                                    }
                                }
                            }
                        }
                    }

                    if ($queryRaw) {
                        $query->whereRaw(rtrim($queryRaw, " and "));
                    }
                    if (count($queryArray) > 0) {
                        $query->where($queryArray);
                    }
                } else {
                    $date = explode(' - ', request()->input($val['name']));
                    $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
                    $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
                    $query = $query->whereBetween(DB::raw('DATE(' . $val['name'] . ')'), array($start, $end));

                    $export .= 'from=' . $start . '&to=' . $end;
                    $countAll = $countAll + 1;
                }

                if ($countAll == 0) {
                    $query->where('id',  "");
                }
            }
            $export .= $val['name'] . '=' . $search[$key] . '&';
        }

        // return $ayam;

        //akhir pencarian --------------------------------
        // relatio
        // sort by
        if ($this->user) {
            if (!Auth::user()->hasRole('superadmin') && !Auth::user()->hasRole('admin')) {
                $query->where('user_id', Auth::user()->id);
            }
        }
        if (Auth::user()->id_unit) {
            $query->where('id', Auth::user()->id_unit);
        }
        if ($this->sort) {
            if ($this->desc) {
                $data = $query->orderBy($this->sort, $this->desc);
            } else {
                $data = $query->orderBy($this->sort);
            }
        }
        //mendapilkan data model setelah query pencarian
        if ($paginate) {
            $data = $query->paginate($paginate);
        } else {
            $data = $query->get();
        }

        // return $button;
        $template = 'template.index';
        if ($this->index) {
            $template = $this->index . '.index';
        }
        // return  $data;

        return view($template,  compact(
            "title",
            "data",
            'searches',
            'hasilSearch',
            'button',
            'tambah',
            'upload',
            'search',
            'export',
            'configHeaders',
            'route'
        ));
    }

    public function create()
    {
        $title = 'Tambah Data Unit';
        $route = 'unit';
        $action = route('unit.store');

        $listJenisUnit = JenisUnit::orderBy('nama')->get();
        $store =  "store";

        return view('unit.form', compact(
            'title',
            'route',
            'store',
            'listJenisUnit',
            'action',
        ));
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute  minimal :min karakter',
            'passwordConfrim.required' => 'password konfirmasi tidak boleh kosong',
            'passwordConfrim.same' => 'password konfirmasi harus sama dengan password',
            'passwordConfrim.min' => 'password konfirmasi minimal 6 karakter',
        ];

        $this->validate(request(), [
            'nama' => 'required|string|unique:unit',
            'singkat' => 'required|string|unique:unit,nama_singkat',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'passwordConfrim' => 'required|same:password|min:6',
            'logo' => 'nullable|mimes:jpeg,bmp,png,jpg',
            'setuju' => 'required',
        ], $messages);

        DB::beginTransaction();
        try {
            DB::commit();
            $logo = $request->logo;
            $nama =  $request->nama;
            $username =  $request->username;
            $slug = Str::slug($nama, '-');
            $adminUnitRole = Role::where('slug', 'admin-unit')->first();
            $pass = bcrypt(request()->input('password'));
            $user = new User;
            $user->name = $username;
            $user->username = $request->username;
            $user->slug = Str::slug($request->username);
            $user->email = request()->input('email');
            $user->password = $pass;
            $user->save();

            $user->role()->sync($adminUnitRole);

            $unit = new Unit();
            $unit->nama = $nama;
            $unit->nama_singkat = $request->singkat;
            $unit->lat_long = str_replace(array('LatLng(', ')'), '', $request->lat_long);
            $unit->email = $request->email;
            $unit->alamat = $request->alamat;
            $unit->detail_alamat = $request->detail_alamat;
            $unit->telepon = $request->telepon;
            $unit->keterangan = $request->keterangan;
            $unit->jenis_unit_id = $request->jenis_unit;
            $unit->setuju = $request->setuju;

            if ($logo) {

                $nama_gambar = $slug . '.' . $logo->getClientOriginalExtension();

                if (!Storage::disk('public')->exists('unit')) {
                    Storage::disk('public')->makeDirectory('unit');
                }

                $path = public_path('storage/unit/' . $nama_gambar);

                $gambar_original = Image::make($logo)->resize(720, 720)->save($path);
                Storage::disk('public')->put('unit/' . $nama_gambar, $gambar_original);

                if (!Storage::disk('public')->exists('unit/thumbnail')) {
                    Storage::disk('public')->makeDirectory('unit/thumbnail');
                }
                $thumbnail = Image::make($logo)->resize(360, 360)->save($path);
                Storage::disk('public')->put('unit/thumbnail/' . $nama_gambar, $thumbnail);
            } else {
                $nama_gambar = NULL;
            }
            $unit->logo = $nama_gambar;


            $unit->user_id = $user->id;
            $unit->save();

            return redirect()->route('unit.index')->with('message', 'Unit berhasil ditambah')->with('Class', 'primary');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('unit.index')->with('message', 'Unit gagal ditambah')->with('Class', 'danger');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        $title =  "Ubah Unit " . $unit->nama;
        $route = 'unit';
        $action = route($route . '.update', $unit->id);
        $listJenisUnit = JenisUnit::orderBy('nama')->get();
        $store =  "update";
        $data = $unit;

        return view('unit.form', compact(
            'title',
            'route',
            'data',
            'store',
            'listJenisUnit',
            'action',
        ));
    }

    public function update(Request $request, Unit $unit)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute  minimal :min karakter',
            'passwordConfrim.required' => 'password konfirmasi tidak boleh kosong',
            'passwordConfrim.same' => 'password konfirmasi harus sama dengan password',
            'passwordConfrim.min' => 'password konfirmasi minimal 6 karakter',
        ];

        $user_id = $unit->user_id;
        $this->validate(request(), [
            'nama' => 'required|string|unique:unit,nama,' . $unit->id,
            'singkat' => 'required|string|unique:unit,nama_singkat,' . $unit->id,
            'username' => 'required|unique:users,username,' . $user_id,
            'email' => 'required|unique:users,email,' . $user_id,
            'password' => 'nullable|min:6',
            'passwordConfrim' => 'nullable|same:password|min:6',
            'detail_alamat' => 'required|string',
            'logo' => 'nullable|mimes:jpeg,bmp,png,jpg',
            'setuju' => 'required',
        ], $messages);

        DB::beginTransaction();
        try {
            DB::commit();

            $logo = $request->logo;
            $nama =  $request->nama;
            $username =  $request->username;
            $slug = Str::slug($nama, '-');
            $adminUnitRole = Role::where('slug', 'admin-unit')->first();
            $pass = bcrypt(request()->input('password'));

            $user = User::find($unit->user_id);
            $user->name = $username;
            $user->username = $request->username;
            $user->slug = Str::slug($request->username);
            $user->email = request()->input('email');
            $user->password = $pass;
            $user->save();

            $user->role()->sync($adminUnitRole);

            $unit->nama = $nama;
            $unit->nama_singkat = $request->singkat;
            // $unit->lat_long = str_replace(array('LatLng(', ')'), '', $request->lat_long);
            $unit->email = $request->email;
            // $unit->alamat = $request->alamat;
            $unit->detail_alamat = $request->detail_alamat;
            $unit->telepon = $request->telepon;
            $unit->keterangan = $request->keterangan;
            $unit->jenis_unit_id = $request->jenis_unit;
            $unit->setuju = $request->setuju;

            if ($logo) {

                $nama_gambar = $slug . '.' . $logo->getClientOriginalExtension();

                if (!Storage::disk('public')->exists('unit')) {
                    Storage::disk('public')->makeDirectory('unit');
                }

                $path = public_path('storage/unit/' . $nama_gambar);

                if (Storage::disk('public')->exists('unit/' . $unit->logo)) {
                    Storage::disk('public')->delete('unit/' . $unit->logo);
                }

                $gambar_original = Image::make($logo)->resize(720, 720)->save($path);
                Storage::disk('public')->put('unit/' . $nama_gambar, $gambar_original);

                if (!Storage::disk('public')->exists('unit/thumbnail')) {
                    Storage::disk('public')->makeDirectory('unit/thumbnail');
                }

                if (Storage::disk('public')->exists('unit/thumbnail/' . $unit->logo)) {
                    Storage::disk('public')->delete('unit/thumbnail/' . $unit->logo);
                }
                $thumbnail = Image::make($logo)->resize(720, 720)->save($path);
                Storage::disk('public')->put('unit/thumbnail/' . $nama_gambar, $thumbnail);
                $unit->logo = $nama_gambar;
            }

            $unit->user_id = $user->id;
            $unit->save();

            return redirect()->route('unit.index')->with('message', 'Unit berhasil ditambah')->with('Class', 'primary');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('unit.index')->with('message', 'Unit gagal ditambah')->with('Class', 'danger');
        }
    }

    public function model()
    {
        return new Unit();
    }
}
