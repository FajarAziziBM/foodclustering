<?php

namespace App\Http\Controllers;

use App\Models\Clustering;
use Illuminate\Http\Request;

class ClusteringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        
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
        return view('pages.klasteringdata');
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
