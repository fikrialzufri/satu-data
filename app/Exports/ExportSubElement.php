<?php

namespace App\Exports;

use App\Models\SubElement;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ExportSubElement implements FromView
{

    protected $id, $year;

    function __construct($id, $year)
    {
        $this->id = $id;
        $this->year = $year;
    }

    public function view(): View
    {
        $data = [];
        $year = $this->year;
        $data = SubElement::where('element_id', $this->id)->orderBy('kode')->get();

        return view('subelement.export', compact(
            'data',
            'year'
        ));
    }
}
