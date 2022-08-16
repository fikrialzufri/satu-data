<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\JenisUnit;
use App\Models\SubElement;
use App\Models\Unit;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{


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
        $aduanPerbulan = [];
        $getAduanPerbulan = [];
        $aduanPerbulanGrafik = [];
        $aduan = [];

        $dataJenisUnit = JenisUnit::all();

        $dataUnit = Unit::orderBy('updated_at', 'desc')->limit(7)->get();

        $aduanCount = 0;
        $pekerjaanCount = 0;
        $rekananCount = 0;

        return view('home.index', compact(
            'title',
            'pegawai',
            'pekerjaanCount',
            'aduanPerbulanGrafik',
            'rekananCount',
            'dataUnit',
            'dataJenisUnit',
            'anggota',
            'aduanCount',
            'rapat',
            'jenisRapat'
        ));
    }
}
