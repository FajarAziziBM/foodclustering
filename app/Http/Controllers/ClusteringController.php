<?php

namespace App\Http\Controllers;

use App\Models\Clustering;
use App\Models\Province;
use Illuminate\Http\Request;
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
            // Mengelompokkan data berdasarkan tahun dan melakukan agregasi di PHP
            $grouped = $provinces->groupBy('tahun')->map(function ($yearGroup) {
                return [
                    'tahun' => $yearGroup->first()->tahun,
                    'namaprovinsi' => $yearGroup->pluck('namaprovinsi')->implode(', '),
                    'luaspanen' => $yearGroup->pluck('luaspanen')->implode(', '),
                    'produktivitas' => $yearGroup->pluck('produktivitas')->implode(', '),
                    'produksi' => $yearGroup->pluck('produksi')->implode(', '),
                ];
            })->values();
            return DataTables::of($grouped)
                ->addColumn('action', function($row) {
                    // Add action buttons or links here
                    $btn = '<td class="text-right">
                                <a class="btn btn-primary btn-sm" target="_blank" href="#">
                                    <i class="fa-thin fa-circle-nodes"></i>
                                </a>
                            </td>';
                    return $btn;
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Clustering $clustering)
    {
        //
        return view('pages.hasilklaster');
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
    public function destroy(Clustering $clustering)
    {
        //
    }
}
