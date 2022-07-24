<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\Legenda;
use App\Models\SubElement;
use App\Models\SubElementTahun;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class SubElementController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'sub_element';
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
        $Element_id = request()->get('element_id');
        $checkElement = Element::where('id', $Element_id)->first();
        if ($checkElement) {
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
                    'input'    => 'hidden',
                    'alias'    => 'element_id',
                    'value' => $Element_id,
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
        if (!auth()->user()->hasRole('superadmin') || !auth()->user()->hasRole('admin')) {
            $unit_id =  auth()->user()->id_unit;
            $checkElement = Element::where('unit_id', $unit_id)->pluck('id')->toArray();
            if ($checkElement) {
                $query = $query->whereIn('element_id', $checkElement);
            }
        } else {

            $checkElement = Element::where('id', $Element_id)->first();
            if ($checkElement) {
                $query = $query->where('element_id', $Element_id);
                $Element = Element::find($Element_id);
                if ($Element) {
                    $Element = $Element->nama;
                    $title =  ucwords($this->route) . " - " . $Element;
                }
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
        //get dari post form
        $getRequest = $this->getRequest($request);
        $element_id = $request->element_id;


        // return $this->configForm();
        $validation = $getRequest['validasi'];
        $messages = $getRequest['messages'];
        //validasi
        $this->validate(
            $request,
            $validation,
            $messages
        );


        DB::beginTransaction();
        try {

            $kodeElement = "";
            $checkElement = Element::find($element_id)->first();
            if ($checkElement) {
                $kodeElement = $checkElement->kode;
            }
            $kode = $kodeElement . "." . $request->kode;

            DB::commit();
            $subElement = new SubElement();
            $subElement->kode = $kode;
            $subElement->nama = $request->nama;
            $subElement->keterangan = $request->keterangan;
            $subElement->sumber_data = $request->sumber_data;
            $subElement->metode_perhitungan = $request->metode_perhitungan;
            $subElement->meta_data = $request->meta_data;
            $subElement->element_id = $request->element_id;
            $subElement->satuan_id = $request->satuan_id;
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
        $getRequest = $this->getRequest($request);
        $element_id = $request->element_id;


        // return $this->configForm();
        $validation = $getRequest['validasi'];
        $messages = $getRequest['messages'];
        //validasi
        $this->validate(
            $request,
            $validation,
            $messages
        );


        DB::beginTransaction();
        try {

            $kodeElement = "";
            $checkElement = Element::find($element_id)->first();
            if ($checkElement) {
                $kodeElement = $checkElement->kode;
            }
            $kode = $kodeElement . "." . $request->kode;

            DB::commit();
            $subElement = SubElement::find($id);
            $subElement->kode = $kode;
            $subElement->nama = $request->nama;
            $subElement->keterangan = $request->keterangan;
            $subElement->sumber_data = $request->sumber_data;
            $subElement->metode_perhitungan = $request->metode_perhitungan;
            $subElement->meta_data = $request->meta_data;
            $subElement->element_id = $request->element_id;
            $subElement->satuan_id = $request->satuan_id;
            $subElement->save();

            return redirect()->route('sub_element.index', "element_id=" . $request->element_id)->with('message', 'Element berhasil diubah')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('sub_element.index', "element_id=" . $request->element_id)->with('message', 'Element gagal diubah')->with('Class', 'danger');
        }
    }

    public function nilai(Request $request)
    {
        $subElement = SubElementTahun::where('sub_element_id', $request->id)->where('tahun',  $request->tahun)->first();

        if (!$subElement) {
            $subElement = new SubElementTahun;
        }

        $subElement->sub_element_id = $request->id;
        $subElement->tahun = $request->tahun;
        $subElement->nilai = $request->value;
        $subElement->legenda_id = $request->legenda_id;
        $subElement->save();

        return $this->sendResponse($subElement, "sukses", 200);
    }
    public function model()
    {
        return new SubElement();
    }
}
