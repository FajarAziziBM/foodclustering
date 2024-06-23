<?php

namespace App\Http\Controllers;

use App\Models\Clustering;
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
                ->addColumn('action', function($row) {
                    $viewBtn = '<a href="' . route('hasilklaster', $row->id) . '" class="btn btn-info btn-sm"> <i class="fa-regular fa-eye"></i></a>';
                    $clusterBtn = '<a href="' . route('sendDatas', ['tahun' => $row->tahun]) . '" class="btn btn-success btn-sm btn-cluster"> <i class="fa-duotone fa-circle-nodes"></i></a>';
                    $deleteBtn = '<a href="' . route('hasilklaster', $row->id) . '" class="btn btn-danger btn-sm"> <i class="fas fa-trash-can"></i></a>';

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
        return view('pages.hasilklater');
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
    public function show(Clustering $clustering)
    {
        //
        return view('pages.finalhasilklaster');
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
    public function destroy($id, Clustering $clustering)
    {
        //
        $clustering = Clustering::findOrFail($id);
        $clustering->delete();
        return redirect()->route('klasteringdata')->with('success', 'Data deleted successfully');

    }
}
