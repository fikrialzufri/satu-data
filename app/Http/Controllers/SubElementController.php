<?php

namespace App\Http\Controllers;

use App\Models\SubElement;
use App\Traits\CrudTrait;

class SubElementController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'sub_element';
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
                'alias'    => 'Nama Sub Element',
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
                'alias'    => 'Nama Sub Element',
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
                'alias'    => 'Nama Sub Element',
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
        return new SubElement();
    }
}
