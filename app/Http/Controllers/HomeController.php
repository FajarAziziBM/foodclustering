<?php

namespace App\Http\Controllers;

use App\Models\Clustering;
use App\Models\HasilCluster;
use App\Models\Province;
use GuzzleHttp\Psr7\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tahun = Clustering::orderBy('tahun', 'desc')->value('tahun');
        $prov = Province::where('tahun', $tahun)->count();
        $luaspanen = Province::where('tahun', $tahun)->pluck('luaspanen')->sum();
        $produktivitas = Province::where('tahun', $tahun)->pluck('produktivitas')->sum();

        $produksi = Province::pluck('produksi')->sum();

        $clusterData = Clustering::where('tahun', $tahun)
            ->get(['eps', 'minpts', 'silhouette_index']);

        $anggota_cluster = HasilCluster::where('tahun', $tahun)
            ->where('cluster', 'cluster_1')
            ->pluck('anggota_cluster')
            ->first();

        $anggota_array = explode(', ', $anggota_cluster);
        $jumlah_anggota = count($anggota_array);

        $anggota_cluster2 = HasilCluster::where('tahun', $tahun)
            ->where('cluster', 'cluster_0')
            ->pluck('anggota_cluster')
            ->first();

        $anggota_array2 = explode(', ', $anggota_cluster2);
        $jumlah_anggota2 = count($anggota_array2);

        $datasgraf= [
            'jumlah_anggota' => $jumlah_anggota,
            'jumlah_anggota2' => $jumlah_anggota,
            'clusterData' => $clusterData,
        ];

        return view('pages.dashboard')->with(compact('prov', 'luaspanen', 'produktivitas', 'produksi', 'tahun', 'jumlah_anggota', 'jumlah_anggota2','datasgraf'));
    }
}
