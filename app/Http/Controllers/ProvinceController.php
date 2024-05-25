<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $provinces = Province::all();
            return DataTables::of($provinces)
                ->addColumn('action', function($row) {
                    // Add action buttons or links here
                    $btn = '<td class="text-right">
                            <a class="btn btn-warning btn-sm" target="_blank" href="#">
                                <i class="fas fa-pen-to-square"></i>
                            </a>
                            <a class="btn btn-danger btn-sm" target="_blank" href="#">
                                <i class="fas fa-trash-can"></i>
                            </a>
                        </td>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.inputdata');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Importing data csv
     */
    public function importdatas(Request $request)
    {
        // Memastikan file diupload melalui request
        if ($request->hasFile('file')) {
            // Mengambil file yang diupload
            $file = $request->file('file');

            // Melakukan import dengan file yang diupload
            Excel::import(new UsersImport, $file->getPathName(), null, \Maatwebsite\Excel\Excel::CSV);

            return redirect('/inputdata')->with('success', 'All good!');
        } else {
            return redirect('/inputdata')->with('error', 'Please upload a file.');
        }
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
    public function show(Province $province)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Province $province)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        //
    }
}
