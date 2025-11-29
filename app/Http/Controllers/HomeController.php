<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Group;
use App\Models\JenisUnit;
use App\Models\Unit;
use App\Services\CkanService;
use Carbon\Carbon;

class HomeController extends Controller
{

    private CkanService $ckanService;

    public function __construct(CkanService $ckanService)
    {
        $this->ckanService = $ckanService;
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title =  "Dashboard";

        $anggota = 0;
        $pegawai = 0;
        $rapat = 0;
        $jenisRapat = 0;

        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        $tahun = Carbon::now()->formatLocalized("%Y");

        $grafikGroup = [];

        $dataJenisUnit = JenisUnit::all();

        $dataUnit = Unit::orderBy('updated_at', 'desc')->limit(7)->get();
        $tahun = Carbon::now()->formatLocalized("%Y");
        $dataGroup = Group::get();

        foreach ($dataGroup as $key => $value) {
            $grafikGroup[] = [
                'country' => $value->nama,
                'visits' => $value->hasSubElement($tahun),

                //  "id": "g1",
                // "valueAxis": "v2",
                // "bullet": "round",
                // "bulletBorderAlpha": 1,
                // "bulletColor": "#FFFFFF",
                // "bulletSize": 8,
                // "hideBulletsCount": 50,
                // "lineThickness": 3,
                // "lineColor": "#2ed8b6",
                // "title": "Data Dasar",
                // "useLineColorForBulletBorder": true,
                // "valueField": "datadasar",
                // "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
            ];
        }

        $aduanCount = 0;
        $pekerjaanCount = 0;
        $rekananCount = 0;

        $ckanVisitors = $this->ckanService->getVisitorStats();
        $ckanVisitorTotals = collect($ckanVisitors)->map(function ($items) {
            return optional($items)->sum('count') ?? 0;
        })->all();

        // Get banner image
        $imageBanner = '';
        $banner = Banner::inRandomOrder()->first();
        if ($banner && $banner->banner) {
            $imageBanner = $banner->banner;
        }

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
            'imageBanner'
        ));
    }
}
