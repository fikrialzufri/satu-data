<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
        $aduanPerbulan = [];
        $getAduanPerbulan = [];
        $aduanPerbulanGrafik = [];
        $aduan = [];

        // $aduan =  Aduan::selectRaw('year(created_at) as tahun, month(created_at) as bulan, count(*) as jumlah')
        //     ->whereYear('created_at', $tahun)
        //     ->groupBy('tahun', 'bulan')
        //     ->orderBy('tahun', 'desc')
        //     ->get();

        // $PelaksanaanPekerjaan =  PelaksanaanPekerjaan::selectRaw('year(created_at) as tahun, month(created_at) as bulan, count(*) as jumlah')
        //     ->whereYear('created_at', $tahun)
        //     ->groupBy('tahun', 'bulan')
        //     ->orderBy('tahun', 'desc')
        //     ->get();

        // $month = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        // foreach ($aduan as $index => $ad) {

        //     $getAduanPerbulan[$ad->bulan] = [
        //         'bulan' => $ad->bulan,
        //         'jumlah' => $ad->jumlah
        //     ];
        // }
        // foreach ($PelaksanaanPekerjaan as $index => $pekerjaan) {

        //     $getPekerjaanPerbulan[$pekerjaan->bulan] = [
        //         'bulan' => $pekerjaan->bulan,
        //         'jumlah' => $pekerjaan->jumlah
        //     ];
        // }



        // foreach ($month as $key => $bulan) {
        //     $aduanPerbulanGrafik[$key] = [
        //         'date' =>  Carbon::create($tahun, $bulan, 1, 0)->format('Y-m'),
        //         'aduan' =>  isset($getAduanPerbulan[$bulan]['jumlah']) ? (int) $getAduanPerbulan[$bulan]['jumlah'] : 0,
        //         'pekerjaan' =>  isset($getPekerjaanPerbulan[$bulan]['jumlah']) ? (int) $getPekerjaanPerbulan[$bulan]['jumlah'] : 0,
        //     ];
        // };
        // $aduanPerbulanGrafik = collect($aduanPerbulanGrafik);

        $aduanCount = 0;
        $pekerjaanCount = 0;
        $rekananCount = 0;

        return view('home.index', compact(
            'title',
            'pegawai',
            'pekerjaanCount',
            'aduanPerbulanGrafik',
            'rekananCount',
            'anggota',
            'aduanCount',
            'rapat',
            'jenisRapat'
        ));
    }
}
