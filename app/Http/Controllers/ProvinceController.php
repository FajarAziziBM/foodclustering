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
            $datas = Province::select('tahun')->distinct()->orderBy('tahun', 'desc')->get();
            return DataTables::of($datas)
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('edit.province', $row->tahun) . '" class="btn btn-warning btn-sm"><i class="fas fa-pen-to-square"></i></a>';
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm btn-cluster"><i class="fas fa-trash-can"></i></button>';
                    $btn = '<td class="text-right">' . $editBtn . ' ' . $deleteBtn . '</td>';
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

            $filename = $file->getClientOriginalName();
            $basename = basename($filename);
            if (preg_match('/\b(\d{4})\b/', $basename, $matches)) {
                $year = $matches[1];
            } else {
                $year = null;
            }

            $fileExtension = strtolower($file->getClientOriginalExtension());

            // Validasi format file CSV
            if ($fileExtension === 'csv') {
                // Melakukan import dengan file yang diupload
                Excel::import(new UsersImport($year), $file->getPathName(), null, \Maatwebsite\Excel\Excel::CSV);
                return redirect('/inputdata')->with('success', 'Data Berhasil Diupload!');
            } else {
                return redirect('/inputdata')->with('error', 'Please upload a .csv file.');
            }
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
        public function edit(Request $request, $id)
        {
            if ($request->ajax()) {
                $datas = Province::where('tahun', $id)->get();
                return DataTables::of($datas)
                    ->addColumn('action', function ($row) {
                        $editBtn = '<a href="' . route('editdataprovinsi', $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-pen-to-square"></i></a>';
                        $btn = '<td class="text-right">' . $editBtn . '</td>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            $province = Province::find($id);
            return view('pages.editdata', compact('province', 'id'));
        }

        public function formedit(Request $request, $id)
        {
            $province = Province::findorfail($id);
            return view('pages.proveditdata', compact('province', 'id'));
        }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request, Province $province)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'namaprovinsi' => 'required|string|max:255',
            'luaspanen' => 'required|numeric',
            'produktivitas' => 'required|numeric',
            'produksi' => 'required|numeric',
            'tahun' => 'required|integer',
        ]);

        $province = Province::findOrFail($id);

        $province->namaprovinsi = $validatedData['namaprovinsi'];
        $province->luaspanen = $validatedData['luaspanen'];
        $province->produktivitas = $validatedData['produktivitas'];
        $province->produksi = $validatedData['produksi'];
        $province->tahun = $validatedData['tahun'];

        $province->save();

        return redirect()->route('update.province', ['id' => $province->tahun])->with('success', 'Data Berhasil Diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tahun, Province $province)
    {
        try {
            Province::where('tahun', $tahun)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
