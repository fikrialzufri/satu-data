<?php

namespace App\Http\Controllers;

use App\Traits\CrudTrait;
use App\Models\Group;

class GroupController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'group';
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
                'alias'    => 'Nama Group',
            ],
            [
                'name'    => 'warna',
                'input'    => 'warna',
                'alias'    => 'Warna',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Group',
                'value'    => null
            ],
        ];
    }
    public function configForm()
    {

        return [
            [
                'name'    => 'kode',
                'input'    => 'text',
                'alias'    => 'Kode Group',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Group',
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

    public function model()
    {
        return new Group();
    }
}
