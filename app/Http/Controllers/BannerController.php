<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use CrudTrait;

    public function model()
    {
        return new Banner();
    }

    public function __construct()
    {
        $this->route = 'banner';
        $this->title = 'Banner';
        $this->tambah = 'false';
        $this->middleware('permission:view-' . $this->route, ['only' => ['show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [

            [
                'name'    => 'nama',
                'alias'    => 'Nama Banner',
            ],
            [
                'name'    => 'banner',
                'input'    => 'image',
                'alias'    => 'Banner',
            ],
        ];
    }
    public function configSearch()
    {
        return [];
    }
    public function configForm()
    {

        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Banner',
                'validasi'    => ['required', 'unique', 'min:1'],
            ],
            [
                'name'    => 'banner',
                'input'    => 'image',
                'alias'    => 'Banner',
                'validasi'    => ['required'],
            ],
        ];
    }
}
