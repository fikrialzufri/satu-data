<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use DB;
use Str;

class OperatorController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'operator';
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
                'alias'    => 'Nama Operator',
            ],
            [
                'name'    => 'username',
                'alias'    => 'Username',
            ],
            [
                'name'    => 'unit',
                'alias'    => 'Unit',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Operator',
                'value'    => null
            ],
        ];
    }
    public function configForm()
    {
        $unit_id =  auth()->user()->id_unit;
        $checkUnit = Unit::where('id', $unit_id)->first();

        if ($checkUnit) {
            return [
                [
                    'name'    => 'nama',
                    'input'    => 'text',
                    'alias'    => 'Nama Operator',
                    'validasi'    => ['required', 'unique', 'min:1'],
                ],
                [
                    'name'    => 'username',
                    'input'    => 'text',
                    'alias'    => 'Username',
                    'validasi'    => ['required', 'unique', 'min:1'],
                ],
                [
                    'name'    => 'email',
                    'input'    => 'email',
                    'alias'    => 'Email',
                    'validasi'    => ['required', 'unique', 'min:1'],
                ],
                [
                    'name'    => 'password',
                    'input'    => 'password',
                    'alias'    => 'Password',
                    'validasi'    => ['required', 'unique', 'min:8'],
                ],
                [
                    'name'    => 'passwordConfrim',
                    'input'    => 'password',
                    'alias'    => 'Password Konfirmasi',
                    'validasi'    => ['required', 'unique', 'min:8'],
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
                    'name'    => 'nama',
                    'input'    => 'text',
                    'alias'    => 'Nama Operator',
                    'validasi'    => ['required', 'unique', 'min:1'],
                ],
                [
                    'name'    => 'username',
                    'input'    => 'text',
                    'alias'    => 'Username',
                    'validasi'    => ['required', 'unique', 'min:1'],
                ],
                [
                    'name'    => 'email',
                    'input'    => 'email',
                    'alias'    => 'Email',
                    'validasi'    => ['required', 'unique', 'min:1'],
                ],
                [
                    'name'    => 'password',
                    'input'    => 'password',
                    'alias'    => 'Password',
                    'validasi'    => ['required', 'unique', 'min:8'],
                ],
                [
                    'name'    => 'passwordConfrim',
                    'input'    => 'password',
                    'alias'    => 'Password Konfirmasi',
                    'validasi'    => ['required', 'unique', 'min:8'],
                ],
                [
                    'name'    => 'unit_id',
                    'input'    => 'combo',
                    'alias'    => 'Unit',
                    'value' => $this->combobox('Unit'),
                    'validasi'    => ['required'],
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
        if ($this->sort) {
            if ($this->desc) {
                $data = $query->orderBy($this->sort, $this->desc);
            } else {
                $data = $query->orderBy($this->sort);
            }
        }
        //mendapilkan data model setelah query pencarian
        if (!auth()->user()->hasRole('superadmin')) {
            $unit_id =  auth()->user()->id_unit;
            $query->where('unit_id', $unit_id);
        }
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

    public function store(Request $request)
    {
        //get dari post form
        $unit_id =  auth()->user()->id_unit;
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
            'same' => 'Password dan konfirmasi password harus sama',
        ];

        $this->validate(request(), [
            'nama' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'passwordConfrim' => 'required|same:password|min:6',
        ], $messages);
        $username =  $request->username;

        DB::beginTransaction();


        $nama =  $request->nama;
        $email =  $request->email;
        try {
            DB::commit();

            $adminOperatorRole = Role::where('slug', 'operator')->first();
            $pass = bcrypt(request()->input('password'));
            $user = new User;
            $user->name = $username;
            $user->username = $request->username;
            $user->slug = Str::slug($request->username);
            $user->email = $email;
            $user->password = $pass;
            $user->save();

            $user->role()->sync($adminOperatorRole);

            $operator = $this->model();
            $operator->nama = $nama;
            if ($unit_id !== null) {
                $operator->unit_id = $unit_id;
            } else {
                $operator->unit_id = $request->unit_id;
            }
            $operator->user_id = $user->id;
            $operator->save();
            return redirect()->route('operator.index')->with('message', 'Operator berhasil ditambah')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();
            User::where('username', $username)->delete();

            return redirect()->route('operator.index')->with('message', 'operator gagal ditambah')->with('Class', 'danger');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Operator $operator)
    {
        //get dari post form
        $unit_id =  auth()->user()->id_unit;
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
            'same' => 'Password dan konfirmasi password harus sama',
        ];

        $this->validate(request(), [
            'nama' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'passwordConfrim' => 'required|same:password|min:6',
        ], $messages);
        $username =  $request->username;

        DB::beginTransaction();


        $nama =  $request->nama;
        $email =  $request->email;
        try {
            DB::commit();

            $adminOperatorRole = Role::where('slug', 'operator')->first();
            $pass = bcrypt(request()->input('password'));
            $user = User::find($operator->user_id);
            $user->name = $username;
            $user->username = $request->username;
            $user->slug = Str::slug($request->username);
            $user->email = $email;
            $user->password = $pass;
            $user->save();

            $user->role()->sync($adminOperatorRole);

            $operator->nama = $nama;
            if ($request->unit_id != $request->unit_id) {
                $operator->unit_id = $request->unit_id;
            } else {
                $operator->unit_id = $unit_id;
            }
            $operator->user_id = $user->id;
            $operator->save();
            return redirect()->route('operator.index')->with('message', 'Operator berhasil diubah')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();
            User::where('username', $username)->delete();

            return redirect()->route('operator.index')->with('message', 'operator gagal diubah')->with('Class', 'danger');
        }
    }

    public function destroy(Operator $operator)
    {
        DB::beginTransaction();

        try {
            DB::commit();

            $operator->delete();
            $user = User::find($operator->user_id);
            if ($user) {
                $user->delete();
            }
            return redirect()->route('operator.index')->with('message', 'Operator berhasil dihapus')->with('Class', 'success');
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->route('operator.index')->with('message', 'operator gagal dihapus')->with('Class', 'danger');
        }
    }

    public function model()
    {
        return new Operator();
    }
}
