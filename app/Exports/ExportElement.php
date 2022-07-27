<?php

namespace App\Exports;

use App\Models\Element;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ExportElement implements FromView
{

    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $data = [];

        $data = Element::where('unit_id', $this->id)->orderBy('kode')->get();

        return view('element.export', compact(
            'data'
        ));
    }
}
