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
        DB::commit();
        return redirect()->route('operator.index')->with('message', 'Operator berhasil diubah')->with('Class', 'success');
        try {
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
