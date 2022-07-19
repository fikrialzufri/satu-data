<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\Unit;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use DB;

class ElementController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'element';
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
                'alias'    => 'Nama Element',
            ],
            [
                'name'    => 'group',
                'alias'    => 'Group',
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

        $unit_id =  $unit_id = request()->get('unit_id');
        $listUnit = [];
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
                    'alias'    => 'Group',
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
                    'validasi'    => ['required', 'unique', 'min:1'],
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
                    'alias'    => 'Group',
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
        if ($unit_id) {
            $query = $query->where('unit_id', $unit_id);
            $unit = Unit::find($unit_id);
            if ($unit) {
                $unit = $unit->nama;
                $title =  ucwords($this->route) . " - " . $unit;
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
        //get dari post form
        $getRequest = $this->getRequest($request);
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
            DB::commit();
            $element = new Element();
            $element->kode = $request->kode;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //open model
        $data = $this->model()->find($id);
        //get dari post form
        $relationId = [];

        //check extra form
        if ($this->extraFrom) {
            foreach ($this->extraFrom as $key => $item) {
                $fileId = $item . '_id';
                $relationId[$fileId] = $data->$fileId;
            }
        }
        // return $request;
        $getRequest = $this->getRequest($request, $id, $relationId);
        $messages = $getRequest['messages'];
        $relation = $getRequest['relation'];
        $validation = $getRequest['validasi'];
        $form = $getRequest['form'];


        //validasi
        $this->validate(
            $request,
            $validation,
            $messages
        );
        //post ke model
        // $this->model()->transaction();
        foreach ($form as $index => $item) {

            if (preg_match("/-image/i", $index)) {
                $route =  $this->route;
                $file =  str_replace("-image", "", $index);
                if ($request->hasFile($file)) {
                    $nama_gambar = Str::slug($route) . '-' . Str::Random(15) . '.' . $request->file($file)->getClientOriginalExtension();

                    $path = public_path('storage/' . $route . '/' . $nama_gambar);

                    if (!Storage::disk('public')->exists($route)) {
                        Storage::disk('public')->makeDirectory($route);
                    }
                    if (!Storage::disk('public')->exists($route . '/thumbnail')) {
                        Storage::disk('public')->makeDirectory($route . '/thumbnail');
                    }

                    // delete gambar original
                    if (Storage::disk('public')->exists($route . '/' . $data->$file)) {
                        Storage::disk('public')->delete($route . '/' . $data->$file);
                    }

                    $gambar_original = Image::make($request->file($file))->save($path);
                    Storage::disk('public')->put($route . '/' . $nama_gambar, $gambar_original);

                    // delete gambar thumbnail
                    if (Storage::disk('public')->exists($route . '/thumbnail' . '/' . $data->$file)) {
                        Storage::disk('public')->delete($route . '/thumbnail' . '/' . $data->$file);
                    }
                    $thumbnail = Image::make($request->file($file))->resize(720, 720)->save($path);
                    Storage::disk('public')->put($route . '/thumbnail' . '/' . $nama_gambar, $thumbnail);

                    $data->$file = $nama_gambar;
                }
                continue;
            }
            if ($index === "password") {
                $item = bcrypt($item);
            }
            if ($this->manyToMany) {
                # code...
                if (in_array(str_replace('_id', '', $index), $this->manyToMany)) {
                    $manyToMany = str_replace('_id', '', $index);
                    continue;
                }
            }
            if ($this->oneToMany) {
                if (in_array(str_replace('_id', '', $index), $this->oneToMany)) {
                    $oneToMany = str_replace('_id', '', $index);
                    continue;
                }
            }

            $data->$index = $item;
        }

        if (isset($relation)) {
            $firstColumn = [];
            if (isset($this->extraFrom)) {

                foreach ($relation as $key => $value) {
                    $relationsFields = $key . '_id';
                    $relationModels = '\\App\Models\\' . ucfirst($key);
                    $relationModels = new $relationModels;
                    $relationModels = $relationModels->find($data->$relationsFields);
                    if ($relationModels) {
                        foreach ($value as $colom => $val) {
                            if ($colom === "password") {
                                $val = bcrypt($val);
                            }
                            if (in_array(str_replace('_id', '', $colom), $this->manyToMany)) {
                                $manyToMany = str_replace('_id', '', $colom);
                                $valueMany[$manyToMany] = $val;
                                continue;
                            }
                            $relationModels->$colom = $val;
                        }
                        $relationModels->save();
                        if (isset($manyToMany)) {
                            $relationModels->$manyToMany()->sync($valueMany);
                        }
                    }
                }
            }
        }

        $data->save();

        if (isset($this->manyToMany)) {
            if (!isset($this->extraFrom)) {
                foreach ($this->manyToMany as  $value) {
                    $hasRalation = 'has' . ucfirst($value);
                    $valueField = $data->$hasRalation()->sync($form[$value]);
                }
            }
        }

        if (isset($this->oneToMany)) {
            foreach ($this->oneToMany as $index => $value) {
                $hasRalation = 'has' . ucfirst($value);
                $idRelation = $value . '_id';

                $valueField = $data->$hasRalation()->sync($form[$idRelation]);
            }
        }

        return redirect()->route('element.index', "unit_id=" . $request->unit_id)->with('message', 'Element berhasil diubah')->with('Class', 'success');
    }

    public function model()
    {
        return new Element();
    }
}
