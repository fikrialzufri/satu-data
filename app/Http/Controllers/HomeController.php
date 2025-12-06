<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Group;
use App\Models\JenisUnit;
use App\Models\Unit;
use App\Models\SubElementTahun;
use App\Models\KategoriInfografik;
use App\Models\Infografik;
use App\Services\CkanService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    private CkanService $ckanService;

    public function __construct(CkanService $ckanService)
    {
        $this->ckanService = $ckanService;
    }

    public function index()
    {
        $title = "Dashboard";

        $anggota = 0;
        $pegawai = 0;
        $rapat = 0;
        $jenisRapat = 0;

        $tahun = Carbon::now()->year;

        $dataJenisUnit = JenisUnit::withCount('hasUnit')->get();
        foreach ($dataJenisUnit as $jenisUnit) {
            $jenisUnit->setAttribute('total', $jenisUnit->has_unit_count);
        }

        $dataUnit = Unit::withCount('hasElement')
            ->orderBy('updated_at', 'desc')
            ->limit(7)
            ->get();

        $unitIds = $dataUnit->pluck('id')->toArray();
        $nilaiTerakhir = [];
        if (!empty($unitIds)) {
            $nilaiTerakhirData = DB::table('sub_element_tahun')
                ->select('element.unit_id', 'sub_element_tahun.nilai', 'sub_element_tahun.updated_at')
                ->join('sub_element', 'sub_element_tahun.sub_element_id', '=', 'sub_element.id')
                ->join('element', 'sub_element.element_id', '=', 'element.id')
                ->whereIn('element.unit_id', $unitIds)
                ->get()
                ->groupBy('unit_id')
                ->map(function ($items) {
                    return $items->sortByDesc('updated_at')->first()->nilai ?? 0;
                })
                ->toArray();

            $nilaiTerakhir = $nilaiTerakhirData;
        }

        foreach ($dataUnit as $unit) {
            $unit->setAttribute('total_element', $unit->has_element_count);
            $unit->setAttribute('nilai_terakhir', $nilaiTerakhir[$unit->id] ?? 0);
        }

        $dataGroup = Group::all();
        $groupIds = $dataGroup->pluck('id')->toArray();

        $grafikGroup = [];
        if (!empty($groupIds)) {
            $subElementTahunCounts = SubElementTahun::select(
                'group.id as group_id',
                DB::raw('COALESCE(SUM(sub_element_tahun.nilai), 0) as total_nilai')
            )
                ->join('sub_element', 'sub_element_tahun.sub_element_id', '=', 'sub_element.id')
                ->join('element', 'sub_element.element_id', '=', 'element.id')
                ->join('group', 'element.group_id', '=', 'group.id')
                ->where('sub_element_tahun.tahun', $tahun)
                ->whereIn('group.id', $groupIds)
                ->groupBy('group.id', 'group.nama')
                ->get()
                ->keyBy('group_id');

            foreach ($dataGroup as $group) {
                $total = $subElementTahunCounts->get($group->id);
                $grafikGroup[] = [
                    'country' => $group->nama,
                    'visits' => $total ? (float) $total->total_nilai : 0,
                ];
            }
        }

        $aduanCount = 0;
        $pekerjaanCount = 0;
        $rekananCount = 0;

        $ckanVisitors = $this->ckanService->getVisitorStats();
        $ckanVisitorTotals = collect($ckanVisitors)->map(function ($items) {
            return optional($items)->sum('count') ?? 0;
        })->all();

        $imageBanner = '';
        $banner = Banner::inRandomOrder()->first();
        if ($banner && $banner->banner) {
            $imageBanner = $banner->banner;
        }

        $kategoriInfografik = KategoriInfografik::with(['infografik' => function ($query) {
            $query->with('thumbnail')->orderBy('created_at', 'desc')->limit(6);
        }])->get();

        $infografikTerbaru = Infografik::with(['kategoriInfografik'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('home.index', compact(
            'title',
            'pegawai',
            'pekerjaanCount',
            'grafikGroup',
            'rekananCount',
            'dataUnit',
            'dataJenisUnit',
            'anggota',
            'aduanCount',
            'rapat',
            'jenisRapat',
            'ckanVisitors',
            'ckanVisitorTotals',
            'imageBanner',
            'kategoriInfografik',
            'infografikTerbaru'
        ));
    }
}
