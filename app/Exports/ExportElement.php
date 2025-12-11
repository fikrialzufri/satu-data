<?php

namespace App\Exports;

use App\Models\Element;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ExportElement implements FromView
{

    protected $id;
    protected $tahun1;
    protected $tahun2;

    function __construct($id, $tahun1 = null, $tahun2 = null)
    {
        $this->id = $id;
        $this->tahun1 = $tahun1;
        $this->tahun2 = $tahun2;
    }

    public function view(): View
    {
        $data = [];

        $data = Element::where('unit_id', $this->id)
            ->with(['hasSubElement.hasSatuan', 'hasSubElement.hasSubElementTahunAll.hasLegenda'])
            ->orderBy('kode')
            ->get();

        $unit = Unit::find($this->id);

        $tahun1 = $this->tahun1;
        $tahun2 = $this->tahun2;

        $tahunList = [];
        if ($tahun1 && $tahun2) {
            for ($i = (int) $tahun1; $i <= (int) $tahun2; $i++) {
                $tahunList[] = $i;
            }
        }

        return view('element.export', compact(
            'data',
            'unit',
            'tahun1',
            'tahun2',
            'tahunList'
        ));
    }
}
