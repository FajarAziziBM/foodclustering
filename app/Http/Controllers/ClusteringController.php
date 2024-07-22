<?php

namespace App\Http\Controllers;

use App\Models\Clustering;
use App\Models\HasilCluster;
use App\Models\Province;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ClusteringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $provinces = Province::all();
            $grouped = $provinces->groupBy('tahun')->map(function ($yearGroup, $key) {
                return (object) [
                    'id' => $key,
                    'tahun' => $yearGroup->first()->tahun,
                    'namaprovinsi' => $yearGroup->pluck('namaprovinsi')->implode(', '),
                    'luaspanen' => $yearGroup->pluck('luaspanen')->implode(', '),
                    'produktivitas' => $yearGroup->pluck('produktivitas')->implode(', '),
                    'produksi' => $yearGroup->pluck('produksi')->implode(', '),
                ];
            })->values();

            return DataTables::of($grouped)
                ->addColumn('action', function ($row) {
                    $viewBtn = '<a href="' . route('hasilklaster', $row->id) . '" class="btn btn-info btn-sm"> <i class="fa-regular fa-eye"></i></a>';
                    $clusterBtn = '<a href="' . route('sendDatas', ['tahun' => $row->tahun]) . '" class="btn btn-success btn-sm btn-cluster"> <i class="fa-duotone fa-circle-nodes"></i></a>';
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" data-id="' . $row->id . '" data-tahun="' . $row->tahun . '" onclick="deleteData(' . $row->id . ')"><i class="fas fa-trash-can"></i></button>';
                    return '<td class="text-right">' . $clusterBtn . ' ' . $viewBtn . ' ' . $deleteBtn . '</td>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.klasteringdata');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function hasilcluster($id, Request $request)
    {
        $idku = $id;

        if ($request->ajax()) {
            $type = $request->input('type', 'datas');

            if ($type == 'datas1') {
                $datas1 = Clustering::where('tahun', $id)
                    ->orderBy('silhouette_index', 'desc')
                    ->orderBy('jmltercluster', 'desc')
                    ->select('eps', 'minpts', 'jmlcluster', 'jmlnoice', 'jmltercluster', 'silhouette_index')
                    ->first();

                if ($datas1) {
                    return DataTables::of([$datas1])->make(true);
                } else {
                    return DataTables::of([])->make(true); // Mengirimkan array kosong jika tidak ada data
                }
            } else {
                $datas = Clustering::where('tahun', $id)
                    ->orderBy('silhouette_index', 'desc')
                    ->orderBy('jmltercluster', 'desc')
                    ->select('eps', 'minpts', 'jmlcluster', 'jmlnoice', 'jmltercluster', 'silhouette_index')
                    ->get();

                if ($datas->count() > 0) {
                    return DataTables::of($datas)->make(true);
                } else {
                    return DataTables::of([])->make(true); // Mengirimkan array kosong jika tidak ada data
                }
            }
        }

        return view('pages.hasilklater', compact('idku'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeClusteringData(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'results' => 'required|array',
            'best_config' => 'required|array',
            'best_labels' => 'required|array',
        ]);

        DB::transaction(function () use ($validatedData) {
            // Clear existing records if needed
            Clustering::query()->delete();
            // Province::query()->delete();

            // Store results
            foreach ($validatedData['results'] as $result) {
                Clustering::create([
                    'provinsiId' => $result['id'],
                    'eps' => $result['EPS'],
                    'eps' => $result['EPS'],
                    'minpts' => $result['MINPTS'],
                    'num_clusters' => $result['NUM_CLUSTERS'],
                    'num_noise' => $result['NUM_NOISE'],
                    'num_clustered' => $result['NUM_CLUSTERED'],
                    'silhouette_index' => $result['SILHOUETTE_INDEX'],
                ]);
            }

            // Store best configuration
            Clustering::create([
                'eps' => $validatedData['best_config']['EPS'],
                'minpts' => $validatedData['best_config']['MINPTS'],
                'num_clusters' => $validatedData['best_config']['NUM_CLUSTERS'],
                'num_noise' => $validatedData['best_config']['NUM_NOISE'],
                'num_clustered' => $validatedData['best_config']['NUM_CLUSTERED'],
                'silhouette_index' => $validatedData['best_config']['SILHOUETTE_INDEX'],
                'is_best' => true,
            ]);

            // Store best labels
            foreach ($validatedData['best_labels'] as $province => $label) {
                Province::create([
                    'province' => $province,
                    'label' => $label
                ]);
            }
        });

        return response()->json(['message' => 'Data stored successfully'], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $latestYear = HasilCluster::max('tahun'); // Ambil tahun terakhir dari tabel hasil_clusters
        $selectedYear = $request->input('tahun', $latestYear ?? date('Y')); // Ambil tahun yang dipilih dari request, default ke tahun terakhir atau tahun saat ini jika tidak ada data
        $availableYears = HasilCluster::distinct()->pluck('tahun')->toArray(); // Ambil tahun-tahun yang tersedia dari database

        $clusterData = HasilCluster::where('tahun', $selectedYear)->get(); // Ambil data klaster untuk tahun yang dipilih

        return view('pages.finalhasilklaster', [
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears,
            'clusterData' => $clusterData,
        ]);
    }

    public function getGrafikClusteringData()
    {
        $latestYear = HasilCluster::max('tahun');
        $selectedYear = request()->input('tahun', $latestYear ?? date('Y'));

        $clusterData = Clustering::where('tahun', $selectedYear)
            ->get(['eps', 'minpts', 'silhouette_index']);

        $anggota_cluster = HasilCluster::where('tahun', $selectedYear)
            ->where('cluster', 'tahan')
            ->pluck('anggota_cluster')
            ->first();

        $anggota_array = explode(', ', $anggota_cluster);
        $jumlah_anggota = count($anggota_array);


        $anggota_cluster2 = HasilCluster::where('tahun', $selectedYear)
        ->where('cluster', 'rentan')
        ->pluck('anggota_cluster')
        ->first();

        $anggota_array2 = explode(', ', $anggota_cluster2);
        $jumlah_anggota2 = count($anggota_array2);

        $jumlah_anggota_total = [
            'anggota' => $jumlah_anggota,
            'anggota2' => $jumlah_anggota2
        ];


        return response()->json([
            'clusterData' => $clusterData,
            'anggota' => $jumlah_anggota_total,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clustering $clustering)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clustering $clustering)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            // Ambil tahun dari request
            $tahun = $request->tahun;

            // Fetch and delete Clustering models
            $datas1 = Clustering::where('tahun', $tahun)->get();
            foreach ($datas1 as $data) {
                $data->delete();
            }

            // Fetch and delete HasilCluster models
            $datas2 = HasilCluster::where('tahun', $tahun)->get();
            foreach ($datas2 as $data) {
                $data->delete();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
        return redirect()->route('klasteringdata')->with('success', 'Data deleted successfully');
    }
}
