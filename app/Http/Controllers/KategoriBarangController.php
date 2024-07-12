<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriBarang = KategoriBarang::all();
        return view('kategori-barang.index', compact('kategoriBarang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori-barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $kategoriBarang = KategoriBarang::create([
            'barang_id' => $request->barang_id,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori barang created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriBarang $kategoriBarang)
    {
        return view('kategori-barang.show', compact('kategoriBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriBarang $kategoriBarang)
    {
        return view('kategori-barang.edit', compact('kategoriBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriBarang $kategoriBarang)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $kategoriBarang->update([
            'barang_id' => $request->barang_id,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori barang updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriBarang $kategoriBarang)
    {
        $kategoriBarang->delete();
        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori barang deleted successfully.');
    }
}