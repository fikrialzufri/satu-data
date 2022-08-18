<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\Group;
use App\Models\JenisData;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;

class JenisDataController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'jenis_data';
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
                'alias'    => 'Nama Jenis',
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
                'alias'    => 'Nama Jenis',
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
                'alias'    => 'Nama Jenis',
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

    public function detail()
    {
        $id = request()->get('id');

        $jenisData = JenisData::find($id);
        $data = [];
        if ($jenisData) {
            $listElement = $jenisData->hasElement()->get();
            $group_id = [];
            foreach ($listElement as $element) {
                $group_id[$element->group_id] = $element->group_id;
            }
            $listGroup = Group::whereIn('id', $group_id)->get();

            foreach ($listGroup as $group) {
                $data[] = [
                    'group' => $group->nama,
                    'total' => $group->hasElementJenis($id),
                    'element' => $group->hasElement()->where('jenis_data_id', $id)->get(),
                ];
            }
        }
        return response()->json($data);
    }

    public function model()
    {
        return new JenisData();
    }
}
