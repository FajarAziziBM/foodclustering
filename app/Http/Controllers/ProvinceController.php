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
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="deleteData(' . $row->tahun . ')"><i class="fas fa-trash-can"></i></button>';
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
                return redirect('/inputdata')->with('success', 'All good!');
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
        $id1 = $id;
        if ($request->ajax()) {
            $datas = Province::all()->where('tahun', $id);
            return DataTables::of($datas)
            ->addColumn('action', function ($row) {
                $editBtn = '<a href="' . route('editdataprovinsi', $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-pen-to-square"></i></a>';
                $btn = '<td class="text-right">' . $editBtn . '</td>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('pages.editdata', compact('id1'));
    }

    public function formedit(Request $request, $id)
    {
        $datas = Province::findorfail($id);
        return view('pages.prov');
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

        // Ambil data berdasarkan ID dari request
        $province = Province::findOrFail($id);

        // Perbarui data dengan nilai baru dari request
        $province->namaprovinsi = $validatedData['namaprovinsi'];
        $province->luaspanen = $validatedData['luaspanen'];
        $province->produktivitas = $validatedData['produktivitas'];
        $province->produksi = $validatedData['produksi'];
        $province->tahun = $validatedData['tahun'];

        // Simpan perubahan ke database
        $province->save();

        return redirect()->route('klasteringdata')->response()->json(['success' => 'Data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Province $province)
    {
        $province = Province::findOrFail($id);
        $province->delete();
        return redirect()->route('inputdata')->with('success', 'Data deleted successfully');
    }
}
