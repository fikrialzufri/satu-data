<?php

namespace App\Http\Controllers;

use App\Exports\ExportSubElement;
use App\Imports\SubElementImport;
use App\Models\Element;
use App\Models\Legenda;
use App\Models\SubElement;
use App\Models\SubElementTahun;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use DB;
use Excel;
use App\Models\Satuan;
use App\Models\Unit;
use Carbon\Carbon;
use Auth;

class SubElementController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'sub_element';
        $this->sort = 'kode';
        $this->middleware('permission:view-sub-element', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-sub-element', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-sub-element', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-sub-element', ['only' => ['delete']]);
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
                'alias'    => 'Nama',
            ],
            [
                'name'    => 'satuan',
                'alias'    => 'Satuan',
            ]
        ];
    }
    public function configSearch()
    {
        $unit_id =  auth()->user()->id_unit;
        if ($unit_id) {
            return [
                [
                    'name'    => 'nama',
                    'input'    => 'text',
                    'alias'    => 'Nama',
                    'value'    => null
                ],
                [
                    'name'    => 'tahun',
                    'input'    => 'year',
                    'default'    => 'year',
                    'alias'    => 'Tahun',
                ],
                [
                    'name'    => 'element_id',
                    'input'    => 'combo',
                    'alias'    => 'Element',
                    'value' => $this->combobox('Element', 'unit_id', '=', $unit_id),
                    'validasi'    => ['required']
                ],
            ];
        } else {
            return [
                [
                    'name'    => 'nama',
                    'input'    => 'text',
                    'alias'    => 'Nama',
                    'value'    => null
                ],
                [
                    'name'    => 'tahun',
                    'input'    => 'year',
                    'default'    => 'year',
                    'alias'    => 'Tahun',
                ],
                [
                    'name'    => 'element_id',
                    'input'    => 'combo',
                    'alias'    => 'Element',
                    'value' => $this->combobox('Element'),
                    'validasi'    => ['required']
                ],
            ];
        }
    }
    public function configForm()
    {

        $Element_id =  $Element_id = request()->get('element_id');
        $checkElement = Element::where('id', $Element_id)->first();

        if ($checkElement) {
            return [
                [
                    'name'    => 'kode',
                    'input'    => 'text',
                    'alias'    => 'Kode',
                    'validasi'    => ['required', 'min:1'],
                ],
                [
                    'name'    => 'nama',
                    'input'    => 'text',
                    'alias'    => 'Nama',
                    'validasi'    => ['required', 'min:1'],
                ],

                [
                    'name'    => 'satuan_id',
                    'input'    => 'combo',
                    'alias'    => 'Satuan',
                    'value' => $this->combobox('Satuan'),
                    'validasi'    => ['required'],
                ],

                [
                    'name'    => 'keterangan',
                    'input'    => 'textarea',
                    'alias'    => 'Keterangan',
                ],
                [
                    'name'    => 'sumber_data',
                    'input'    => 'textarea',
                    'alias'    => 'Sumber Data',
                ],
                [
                    'name'    => 'metode_perhitungan',
                    'input'    => 'textarea',
                    'alias'    => 'Metode Perhitungan',
                ],
                [
                    'name'    => 'meta_data',
                    'input'    => 'textarea',
                    'alias'    => 'Meta Data',
                ],
                [
                    'name'    => 'element_id',
                    'input'    => 'hidden',
                    'alias'    => 'element_id',
                    'value' => $Element_id,
                ],
            ];
        } else {
            return [
                [
                    'name'    => 'kode',
                    'input'    => 'text',
                    'alias'    => 'Kode',
                    'validasi'    => ['required', 'min:1'],
                ],
                [
                    'name'    => 'nama',
                    'input'    => 'text',
                    'alias'    => 'Nama',
                    'validasi'    => ['required', 'min:1'],
                ],
                [
                    'name'    => 'element_id',
                    'input'    => 'combo',
                    'alias'    => 'Element',
                    'value' => $this->combobox('Element'),
                    'validasi'    => ['required']
                ],
                [
                    'name'    => 'satuan_id',
                    'input'    => 'combo',
                    'alias'    => 'Satuan',
                    'value' => $this->combobox('Satuan'),
                    'validasi'    => ['required'],
                ],

                [
                    'name'    => 'keterangan',
                    'input'    => 'textarea',
                    'alias'    => 'Keterangan',
                ],
                [
                    'name'    => 'sumber_data',
                    'input'    => 'textarea',
                    'alias'    => 'Sumber Data',
                ],
                [
                    'name'    => 'meta_data',
                    'input'    => 'textarea',
                    'alias'    => 'Meta Data',
                ],
                [
                    'name'    => 'metode_perhitungan',
                    'input'    => 'textarea',
                    'alias'    => 'Metode Perhitungan',
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
                if ($val['input'] != 'daterange' && $val['input'] != 'year') {
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
        $Element_id = request()->get('element_id');

        if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin')) {
            $checkElement = Element::where('id', $Element_id)->first();
            if ($checkElement) {
                $query = $query->where('element_id', $Element_id);
                $Element = Element::find($Element_id);
                if ($Element) {
                    $Element = $Element->nama;
                    $title =  ucwords($this->route) . " - " . $Element;
                }
            }
        } else {
            $unit_id =  auth()->user()->id_unit;
            $checkElement = Element::where('unit_id', $unit_id)->where('id', $Element_id);
            if ($checkElement) {
                $Element = $checkElement->first();
                if ($Element) {
                    # code...
                    $title =  ucwords($this->route) . " - " . $Element->nama;
                    $query = $query->whereIn('element_id', $checkElement->pluck('id')->toArray());
                }
            }
        }

        $sub_element_id = $query->pluck('id')->toArray();
        $userUpdated = SubElementTahun::orderBy('updated_at', 'desc')->whereIn('sub_element_id', $sub_element_id)->first();

        if ($this->sort) {
            if ($this->desc) {
                $data = $query->orderBy($this->sort, $this->desc);
            } else {
                $data = $query->orderBy($this->sort);
            }
        }
        // ambil semua sub elemennt id
        //mendapilkan data model setelah query pencarian

        if ($paginate) {
            // return $data = $query->toSql();
            $data = $query->paginate($paginate);
        } else {
            $data = $query->get();
        }
        $listLegenda = Legenda::orderBy('nama')->get();

        $year = Carbon::now()->year;
        if (request()->get('tahun') != null) {
            $year = request()->get('tahun');
        }


        $subYear = Carbon::now()->subYears(1)->year;

        if (request()->get('tahun') != null) {
            $subYear = request()->get('tahun') - 1;
        }
        // return $button;
        $template = 'subelement.index';
        // return  $data;

        return view($template,  compact(
            "title",
            "Element_id",
            'listLegenda',
            'subYear',
            'year',
            "data",
            'searches',
            'hasilSearch',
            'userUpdated',
            'button',
            'tambah',
            'upload',
            'userUpdated',
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
        $element_id = $request->element_id;
        $user_id= auth()->user()->id;
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama'
        ];

        $this->validate(request(), [
            'kode' => "required|unique:sub_element,kode,null,id,element_id,$element_id",
            'nama' => "required",
            'satuan_id' => "required",
            'element_id' => "required",
        ], $messages);


        DB::beginTransaction();
        try {

            DB::commit();
            $subElement = new SubElement();
            $subElement->kode = $request->kode;
            $subElement->nama = $request->nama;
            $subElement->keterangan = $request->keterangan;
            $subElement->sumber_data = $request->sumber_data;
            $subElement->metode_perhitungan = $request->metode_perhitungan;
            $subElement->meta_data = $request->meta_data;
            $subElement->element_id = $element_id;
            $subElement->satuan_id = $request->satuan_id;
            $subElement->user_id = $user_id;
            $subElement->save();

            return redirect()->route('sub_element.index', "element_id=" . $request->element_id)->with('message', 'Element berhasil ditambah')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('sub_element.index', "element_id=" . $request->element_id)->with('message', 'Element gagal ditambah')->with('Class', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { //get dari post form
        $element_id = $request->element_id;
        $user_id= auth()->user()->id;

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama'
        ];

        $this->validate(request(), [
            'kode' => "required|unique:sub_element,kode,$id,id,element_id,$element_id",
            'nama' => "required",
            'satuan_id' => "required",
            'element_id' => "required",
        ], $messages);


        DB::beginTransaction();
        $subElement = SubElement::find($id);
        $subElement->kode =  $request->kode;
        $subElement->nama = $request->nama;
        $subElement->keterangan = $request->keterangan;
        $subElement->sumber_data = $request->sumber_data;
        $subElement->metode_perhitungan = $request->metode_perhitungan;
        $subElement->meta_data = $request->meta_data;
        $subElement->element_id =  $element_id;
        $subElement->satuan_id = $request->satuan_id;
        $subElement->user_id = $user_id;
        $subElement->save();
        try {

            DB::commit();
            return redirect()->route('sub_element.index', "element_id=" . $request->element_id)->with('message', 'Element berhasil diubah')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('sub_element.index', "element_id=" . $request->element_id)->with('message', 'Element gagal diubah')->with('Class', 'danger');
        }
    }

    public function nilai(Request $request)
    {
        $subElement = SubElementTahun::where('sub_element_id', $request->id)->where('tahun',  $request->tahun)->first();

        $user_id= auth()->user()->id;
        if (!$subElement) {
            $subElement = new SubElementTahun;
        }
        if ($request->parent) {
            $subElement = SubElement::find($request->id);

            $subElement->parent = $request->parent === "true" ? "Y" : "N";
            $subElement->user_id = Auth::user()->id;

            $subElement->save();
            $updateAd =  $subElement->created_at;
        } else {

            $subElement->sub_element_id = $request->id;
            $subElement->tahun = $request->tahun;
            $subElement->nilai = str_replace(".", "", $request->value);
            $subElement->legenda_id = $request->legenda_id;
            $subElement->save();
            $updateAd =  $subElement->updated_at;
        }
        $subElement = SubElement::find($subElement->sub_element_id);

        $element = Element::find($subElement->element_id);

        if ($element) {
            $unit = Unit::find($element->unit_id);
            if ($unit) {
                $element->updated_at = $updateAd;
                $element->save();

                $unit->updated_at = $updateAd;
                $unit->save();
            }
        }

        return $this->sendResponse($subElement, "sukses", 200);
    }

    public function import()
    {
        $title =  "Import " . ucwords($this->route);
        $action = route('sub-element.import.post');
        $route = $this->route;
        $unit_id =  auth()->user()->id_unit;
        $element_id =  request()->get('element_id');
        $tahun =  request()->get('tahun');
        $checkElement = Element::find($element_id);

        if (!$checkElement) {
            $element_id =  null;
        }
        return view('subelement.import', compact(
            'title',
            'action',
            'tahun',
            'element_id',
            'route'
        ));
    }
    public function importpost(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx',
        ]);

        DB::beginTransaction();
        $element_id = $request->element_id;
        $tahun = $request->tahun;
        $listElement = [];

        $legenda_id = Legenda::whereSlug('tetap')->first()->id;

        if ($request->hasFile('file')) {
            try {
                DB::commit();
                $file = $request->file('file');
                $element = Excel::toArray(new SubElementImport, $file);

                foreach ($element[0] as $key => $value) {
                    $listElement[$key] = [
                        'kode' => $value[1],
                        'nama' => $value[2],
                        'nilai' => $value[3],
                        'tahun' => str_replace(".", "", $value[4]),
                        'satuan' => $value[5],
                        'keterangan' => $value[6],
                        'sumber_data' => $value[7],
                        'metode_perhitungan' => $value[8],
                        'meta_data' => $value[9],
                    ];
                }
                $checkElement = [];
                $subElement = [];
                foreach ($listElement as $el => $value) {
                    $checkElement[$el] = SubElement::where('kode', $value['kode'])->where('element_id', $element_id)->first();
                    $dataSatuan[$el] = Satuan::where('nama', 'like', '%' . $value['satuan'] . '%')->first();

                    if (!$checkElement[$el]) {

                        if (!$dataSatuan[$el]) {
                            if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin')) {
                                $dataSatuan[$el] = new Satuan();
                                $dataSatuan[$el]->nama = $value['satuan'];
                                $dataSatuan[$el]->save();
                            } else {
                                if (auth()->user()->can("create-sub-element")) {
                                    $dataSatuan[$el] = new Satuan();
                                    $dataSatuan[$el]->nama = $value['satuan'];
                                    $dataSatuan[$el]->save();
                                }
                            }
                        }
                        if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin')) {
                            $checkElement[$el] = new SubElement();
                            $checkElement[$el]->kode = $value['kode'];
                            $checkElement[$el]->nama = $value['nama'];
                            $checkElement[$el]->keterangan = $value['keterangan'];
                            $checkElement[$el]->metode_perhitungan = $value['metode_perhitungan'];
                            $checkElement[$el]->meta_data = $value['meta_data'];
                            $checkElement[$el]->satuan_id = $dataSatuan[$el]->id;
                            $checkElement[$el]->element_id = $element_id;
                            $checkElement[$el]->user_id = Auth::user()->id;
                            $checkElement[$el]->save();
                        } else {
                            if (auth()->user()->can("create-sub-element")) {
                                $checkElement[$el] = new SubElement();
                                $checkElement[$el]->kode = $value['kode'];
                                $checkElement[$el]->nama = $value['nama'];
                                $checkElement[$el]->keterangan = $value['keterangan'];
                                $checkElement[$el]->metode_perhitungan = $value['metode_perhitungan'];
                                $checkElement[$el]->meta_data = $value['meta_data'];
                                $checkElement[$el]->element_id = $element_id;
                                $checkElement[$el]->user_id = Auth::user()->id;

                                $checkElement[$el]->save();
                            }
                        }
                    }
                    $subElement[$el] = SubElementTahun::where('sub_element_id', $checkElement[$el]->id)->where('tahun',  $value['tahun'])->first();
                    if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin')) {

                        if (!$subElement[$el]) {
                            $subElement[$el] = new SubElementTahun;
                            $subElement[$el]->sub_element_id = $checkElement[$el]->id;
                        }
                        $subElement[$el]->legenda_id = $legenda_id;
                        $subElement[$el]->tahun = $value['tahun'];
                        $subElement[$el]->nilai =  $value['nilai'];
                        $subElement[$el]->user_id = Auth::user()->id;

                        $subElement[$el]->save();
                        $updateAd[$el] =  $subElement[$el]->created_at;
                    } else {

                        if (!$subElement[$el]) {
                            $subElement[$el] = new SubElementTahun;
                            $subElement[$el]->sub_element_id = $checkElement[$el]->id;
                            $subElement[$el]->legenda_id = $legenda_id;
                        }
                        $subElement[$el]->tahun = $value['tahun'];
                        $subElement[$el]->nilai =  $value['nilai'];
                        $subElement[$el]->user_id = Auth::user()->id;

                        $subElement[$el]->save();
                        $updateAd[$el] =  $subElement[$el]->updated_at;
                    }
                    $subElementParent[$el] = SubElement::find($subElement[$el]->sub_element_id);

                    $element[$el] = Element::find($subElementParent[$el]->element_id);

                    if ($element[$el]) {
                        $unit[$el] = Unit::find($element[$el]->unit_id);
                        if ($unit[$el]) {
                            $element[$el]->updated_at = $updateAd[$el];
                            $element[$el]->save();

                            $unit[$el]->updated_at = $updateAd[$el];
                            $unit[$el]->save();
                        }
                    }
                }
                return redirect()->route('sub_element.index', "element_id=" . $element_id . "&tahun=" . $tahun)->with('message', 'Element berhasil ditambah')->with('Class', 'success');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route('sub_element.index', "element_id=" . $element_id . "&tahun=" . $tahun)->with('message', 'Element berhasil ditambah')->with('Class', 'success');
            }
        }

        return redirect()->route($this->route . '.index')->with('message', 'Element Gagal Import Roster')->with('Class', 'dangger');
    }

    public function download()
    {
        $year = Carbon::now()->year;
        if (request()->get('tahun') != null) {
            $year = request()->get('tahun');
        }

        $element_id = request()->get('element_id');

        if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin')) {
            $checkElement = Element::find($element_id);
            if ($checkElement) {
                $id = $checkElement->id;
            }
        } else {
            $unit_id =  auth()->user()->id_unit;
            $checkElement = Element::where('unit_id', $unit_id)->where('id', $element_id)->first();
            if ($checkElement) {
                $id = $checkElement->id;
            } else {
                $id = null;
                // return redirect()->route($this->route . '.index')->with('message', 'Download Element Gagal, Mohon tidak merubah element')->with('Class', 'dangger');
            }
        }
        return Excel::download(new ExportSubElement($id, $year), 'Download Sub Element.xlsx');
    }

    public function kategorielement()
    {
        //memangil model peratama
        $title = "";
        $nama_unit = "";
        $keterangan = "";
        $dokumentasi = "";
        $query = $this->model()::query();
        $element_id = request()->get('element_id');

        $checkElement = Element::where('id', $element_id)->first();
        if ($checkElement) {
            $query = $query->where('element_id', $element_id);
            $Element = Element::find($element_id);
            if ($Element) {
                $title =  $Element->nama;
                $nama_unit = $Element->unit;
                $keterangan = $Element->keterangan;
                $dokumentasi = $Element->dokumentasi;
            }
        }
        $data = $query->get();
        $totaldata = 0;

        if ($data) {
            $totaldata = count($data);
        }
        $tahun = Carbon::now()->year;
        if (request()->get('tahun') != null) {
            $tahun = request()->get('tahun');
        }

        $subtahun = Carbon::now()->subYears(1)->year;

        if (request()->get('subtahun') != null) {
            $subtahun = request()->get('subtahun');
        }

        // for loop tahun dan subtahun
        $listtahun = [];
        for ($i = $subtahun; $i < $tahun + 1; $i++) {
            $listtahun[] = (int) $i;
        }

        $listLegenda = Legenda::all();
        $url = route('kelompokelement.api') . "?element_id=" . $element_id . "&subtahun=" . $subtahun . "&tahun=" . $tahun;

        // lenght listh tahun
        return view('kategorielement.index',  compact(
            "title",
            "element_id",
            "tahun",
            "url",
            "subtahun",
            "totaldata",
            "listtahun",
            "nama_unit",
            "listLegenda",
            "keterangan",
            "dokumentasi",
            'data'
        ));
    }

    public function kelompokelement()
    {
        $id = request()->get('id');
        $element_id = request()->get('element_id');
        $tahun = Carbon::now()->year;
        if (request()->get('tahun') != null) {
            $tahun = request()->get('tahun');
        }

        $subtahun = Carbon::now()->subYears(1)->year;

        if (request()->get('subtahun') != null) {
            $subtahun = request()->get('subtahun');
        }
        $data = $this->model()->find($id);
        // for loop tahun dan subtahun
        $listtahun = [];
        $no = 0;
        for ($i = $subtahun; $i < $tahun + 1; $i++) {
            $listtahun[$no++] = [
                'nilai' => format_uang($data->hasSubElementTahun((int) $i)),
                'visits' => $data->hasSubElementTahun((int) $i),
                'legenda' => $data->hasLegenda((int) $i),
                'satuan' => $data->satuan,
                'tahun' => (int) $i,
                'country' => (int) $i,
            ];
        }

        $result =  [
            'nama' => $data->nama,
            'nilai' => $listtahun
        ];
        return $this->sendResponse($result, "sukses", 200);
    }

    public function apikategorielement()
    {
        $query = $this->model()::query();
        $element_id = request()->get('element_id');

        $checkElement = Element::where('id', $element_id)->first();
        if ($checkElement) {
            $query = $query->where('element_id', $element_id);
            $Element = Element::find($element_id);
            if ($Element) {
                $title =  $Element->nama;
                $nama_unit = $Element->unit;
                $keterangan = $Element->keterangan;
                $dokumentasi = $Element->dokumentasi;
            }
        }
        $data = $query->get();


        $tahun = Carbon::now()->year;
        if (request()->get('tahun') != null) {
            $tahun = request()->get('tahun');
        }

        $subtahun = Carbon::now()->subYears(1)->year;

        if (request()->get('subtahun') != null) {
            $subtahun = request()->get('subtahun');
        }

        // for loop tahun dan subtahun
        $listtahun = [];
        $listnilai = [];

        $result = [];
        $no = 0;
        foreach ($data as $key => $value) {
            for ($i = $subtahun; $i < $tahun + 1; $i++) {

                $listnilai[$key][$no++] = [
                    'nilai' => format_uang($value->hasSubElementTahun((int) $i)),
                    'legenda' => $value->hasLegenda((int) $i),
                    'satuan' => $value->satuan,
                    'tahun' => (int) $i
                ];
                // total nilai

            }
            $result[] = [
                'nama' => $value->nama,
                'satuan' => $value->satuan,
                'jenis' => $value->jenis,
                'group' => $value->group,
                'nilai' => $listnilai[$key]
            ];
        }

        // lenght listh tahun
        return $this->sendResponse($result, "sukses", 200, $no);
    }

    public function model()
    {
        return new SubElement();
    }
}
