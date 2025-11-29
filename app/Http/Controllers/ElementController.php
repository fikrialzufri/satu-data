<?php

namespace App\Http\Controllers;

use App\Exports\ExportElement;
use App\Imports\ElementImport;
use App\Models\Element;
use App\Models\Group;
use App\Models\JenisData;
use App\Models\Unit;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use DB;
use Excel;
use Auth;
use Str;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ElementController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'element';
        $this->kelipatan = 12;
        $this->middleware('permission:view-' . $this->route, ['only' => ['show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'kode',
                'alias'    => 'Kode',
            ],
            [
                'name'    => 'nama',
                'alias'    => 'Nama Element',
            ],
            [
                'name'    => 'group',
                'alias'    => 'Urusan',
            ],
            [
                'name'    => 'jenis_data',
                'alias'    => 'Jenis Data',
            ]
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Element',
                'value'    => null
            ],
        ];
    }
    public function configForm()
    {

        $unit_id =  request()->get('unit_id');
        $checkUnit = Unit::where('id', $unit_id)->first();

        if ($checkUnit) {
            return [
                [
                    'name'    => 'kode',
                    'input'    => 'text',
                    'alias'    => 'Kode',
                    'validasi'    => ['required', 'unique', 'min:1'],
                ],
                [
                    'name'    => 'nama',
                    'input'    => 'text',
                    'alias'    => 'Nama',
                    'validasi'    => ['required', 'unique', 'min:1'],
                ],

                [
                    'name'    => 'group_id',
                    'input'    => 'combo',
                    'alias'    => 'Urusan',
                    'value' => $this->combobox('Group'),
                    'validasi'    => ['required'],
                ],
                [
                    'name'    => 'jenis_data_id',
                    'input'    => 'combo',
                    'alias'    => 'Jenis Data',
                    'value' => $this->combobox('JenisData'),
                    'validasi'    => ['required'],
                ],
                [
                    'name'    => 'keterangan',
                    'input'    => 'textarea',
                    'alias'    => 'Keterangan',
                ],
                [
                    'name'    => 'dokumentasi',
                    'input'    => 'textarea',
                    'alias'    => 'Dokumentasi',
                ],
                [
                    'name'    => 'unit_id',
                    'input'    => 'hidden',
                    'alias'    => 'unit_id',
                    'value' => $unit_id,
                ],
            ];
        } else {
            return [
                [
                    'name'    => 'kode',
                    'input'    => 'text',
                    'alias'    => 'Kode',
                    'validasi'    => ['required', 'unique', 'min:1'],
                ],
                [
                    'name'    => 'nama',
                    'input'    => 'text',
                    'alias'    => 'Nama',
                    'validasi'    => ['required', 'min:1'],
                ],
                [
                    'name'    => 'unit_id',
                    'input'    => 'combo',
                    'alias'    => 'Unit',
                    'value' => $this->combobox('Unit'),
                    'validasi'    => ['required']
                ],
                [
                    'name'    => 'group_id',
                    'input'    => 'combo',
                    'alias'    => 'Urusan',
                    'value' => $this->combobox('Group'),
                    'validasi'    => ['required'],
                ],
                [
                    'name'    => 'jenis_data_id',
                    'input'    => 'combo',
                    'alias'    => 'Jenis Data',
                    'value' => $this->combobox('JenisData'),
                    'validasi'    => ['required'],
                ],
                [
                    'name'    => 'keterangan',
                    'input'    => 'textarea',
                    'alias'    => 'Keterangan',
                ],
                [
                    'name'    => 'dokumentasi',
                    'input'    => 'textarea',
                    'alias'    => 'Dokumentasi',
                ],
            ];
        }
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
        $unit_id = request()->get('unit_id');
        if (Auth::user()) {
            if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin')) {


                $unit_id = request()->get('unit_id');
                if ($unit_id) {
                    $query = $query->where('unit_id', $unit_id);
                    $unit = Unit::find($unit_id);
                    if ($unit) {
                        $unit = $unit->nama;
                        $title =  ucwords($this->route) . " - " . $unit;
                    }
                }
            } else {
                $unit_id =  auth()->user()->id_unit;
                $query->where('unit_id', $unit_id);
            }
        } else {
            $unit_id =  request()->get('unit_id');
            if ($unit_id) {
                $query->where('unit_id', $unit_id);
            }
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
        $template = 'element.index';
        // return  $data;

        return view($template,  compact(
            "title",
            "unit_id",
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //nama title
        if (!isset($this->title)) {
            $title =  "Tambah " . ucwords($this->route);
        } else {
            $title =  "Tambah " . ucwords($this->title);
        }

        //nama route dan action route
        $route =  $this->route;
        $store =  "store";

        //memanggil config form
        $form = $this->configform();

        $count = count($form);

        $colomField = $this->colomField($count);

        $countColom = $this->countColom($count);
        $countColomFooter = $this->countColomFooter($count);
        // $hasValue = $this->hasValue;

        $unit_id = request()->get('unit_id');
        if ($unit_id) {
            $unit = Unit::find($unit_id);
            if ($unit) {
                $unit = $unit->nama;
                $title = "Tambah " . ucwords($this->route) . " - " . $unit;
            }
        }

        return view('template.form', compact(
            'title',
            'form',
            'countColom',
            'colomField',
            'countColomFooter',
            'store',
            'route'
            // 'hasValue'
        ));
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama'
        ];

        $this->validate(request(), [
            'kode' => "required|unique:element,kode,null,id,unit_id,$request->unit_id",
            'nama' => "required",
            'group_id' => "required",
            'unit_id' => "required",
        ], $messages);

        DB::beginTransaction();
        try {

            $element = new Element();
            $element->kode =  $request->kode;
            $element->nama = $request->nama;
            $element->group_id = $request->group_id;
            $element->jenis_data_id = $request->jenis_data_id;
            $element->keterangan = $request->keterangan;
            $element->dokumentasi = $request->dokumentasi;
            $element->unit_id = $request->unit_id;
            $element->save();
            DB::commit();
            return redirect()->route('element.index', "unit_id=" . $request->unit_id)->with('message', 'Element berhasil ditambah')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('element.index', "unit_id=" . $request->unit_id)->with('message', 'Element gagal ditambah')->with('Class', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
            'same' => 'Password dan konfirmasi password harus sama',
        ];

        $this->validate(request(), [
            'kode' => "required|unique:element,kode,$id,id,unit_id,$request->unit_id",
            'nama' => "required",
            'group_id' => "required",
            'unit_id' => "required",
        ], $messages);


        DB::beginTransaction();
        try {
            DB::commit();
            $element = Element::find($id);

            $element->kode =  $request->kode;
            $element->nama = $request->nama;
            $element->group_id = $request->group_id;
            $element->jenis_data_id = $request->jenis_data_id;
            $element->keterangan = $request->keterangan;
            $element->dokumentasi = $request->dokumentasi;
            $element->unit_id = $request->unit_id;
            $element->save();
            return redirect()->route('element.index', "unit_id=" . $request->unit_id)->with('message', 'Element berhasil ditambah')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('element.index', "unit_id=" . $request->unit_id)->with('message', 'Element gagal ditambah')->with('Class', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $element = Element::find($id);
        $element->delete();

        return redirect()->route('element.index', "unit_id=" . $element->unit_id)->with('message', 'Element berhasil dihapus')->with('Class', 'success');
    }


    public function import()
    {
        $title =  "Import " . ucwords($this->route);
        $action = route('element.import.post');
        $route = $this->route;
        $listGroup = Group::orderBy('nama')->get();
        $listJenisData = JenisData::orderBy('nama')->get();
        $listUnit = Unit::orderBy('nama')->get();
        $unit_id =  auth()->user()->id_unit;
        return view('element.import', compact(
            'title',
            'action',
            'listGroup',
            'unit_id',
            'listUnit',
            'route',
            'listJenisData'
        ));
    }
    public function importpost(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx',
            'group_id' => 'required',
            'jenis_data_id' => 'required',
        ]);

        DB::beginTransaction();
        $group_id = $request->group_id;
        $jenis_data_id = $request->jenis_data_id;
        $unit_id = $request->unit_id;
        $listElement = [];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $element = Excel::toArray(new ElementImport, $file);

            foreach ($element[0] as $key => $value) {
                $listElement[$key] = [
                    'kode' => $value[1],
                    'nama' => $value[2],
                    'keterangan' => $value[3],
                    'dokumentasi' => $value[4],
                ];
            }

            try {
                DB::commit();
                $checkElement = [];
                foreach ($listElement as $el => $value) {
                    $checkElement[$el] = Element::where('kode', $value['kode'])->first();
                    if (!$checkElement[$el]) {
                        $checkElement[$el] = new Element();
                    }
                    $checkElement[$el]->kode = $value['kode'];
                    $checkElement[$el]->nama = $value['nama'];
                    $checkElement[$el]->group_id = $group_id;
                    $checkElement[$el]->jenis_data_id = $jenis_data_id;
                    $checkElement[$el]->keterangan = $value['keterangan'];
                    $checkElement[$el]->dokumentasi = $value['dokumentasi'];
                    $checkElement[$el]->unit_id = $unit_id;
                    $checkElement[$el]->save();
                }
                return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Berhasil Import Roster')->with('Class', 'success');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Gagal Import Roster')->with('Class', 'danger');
            }
        }

        return redirect()->route($this->route . '.index')->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Gagal Import Roster')->with('Class', 'dangger');
    }

    public function download()
    {
        if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin')) {


            $unit_id = request()->get('unit_id');
            if ($unit_id) {
                $unit = Unit::find($unit_id);
                if ($unit) {
                    $id = $unit->id;
                }
            }else{
                // back dengan message mohon pilih unit
                return redirect()->back()->with('message', 'Mohon pilih unit')->with('Class', 'danger');
            }
        } else {
            $id =  auth()->user()->id_unit;
            $unit = Unit::find($id);
        }

        $tahun1 = request()->get('tahun1', Carbon::now()->subYears(1)->year);
        $tahun2 = request()->get('tahun2', Carbon::now()->year);

        $namaUnit = $unit->nama;
        return Excel::download(new ExportElement($id, $tahun1, $tahun2), 'Download Element ' . $namaUnit . ' - ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

     /**
     * Send CKAN.
     *
     * @param  \App\Models\Dinamis
     * @return \Illuminate\Http\Response
     */
    public function sendckan($id)
    {
        $data = $this->model()->find($id);

        $page = request()->get('page');

        $unit_id = $data->unit_id;

        $unit = Unit::find($unit_id);

        $owner_org =  Str::slug($unit->nama_singkat);
        $nama =  Str::slug($data->nama . $owner_org);
        $title =  $data->nama;
        $notes =  $data->keterangan;

        $apiURL = env('CKAN_ENDPOINT') . '/api/action/package_create';

        $postInput = [
            'name' => $nama,
            'title' => $title,
            'aliases' => $title,
            'notes' => $notes,
            'owner_org' => $owner_org,
        ];

        // Headers
        $headers = [
            'Authorization' => env('CKAN_AUTH'),
            'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $postInput);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody['success'] == false) {
            return redirect()->route($this->route . '.index', "unit_id=" . $unit_id)->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' Sudah ada di CKAN')->with('Class', 'danger')->with('icon', 'error');
        }

        return redirect()->route($this->route . '.index', "unit_id=" . $unit_id)->with('message', ucwords(str_replace('-', ' ', $this->route)) . ' berhasil dikirim ke ckan')->with('Class', 'success')->with('icon', 'success');
    }
    
    public function model()
    {
        return new Element();
    }
}
