<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Kelola Kategori';
        $kategori = Kategori::with('barangs')->paginate(10);
        return view('pages.Kategori.kelola-kategori', compact('title', 'kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        $title = 'Tambah Kategori';
        return view('pages.Kategori.add-kategori', compact('title', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $newName = null; // inisialisasi $newName untuk penanganan gambar


        // Simpan data lokasi ke dalam tabel 'lokasis'
        $kategori = Kategori::create([
            'nama' => $request->nama,
        ]);

        if ($kategori) {
            session()->flash('status', 'success');
            session()->flash('message', 'Kategori berhasil ditambahkan.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menambahkan kategori. Silakan coba lagi.');
        }

        return redirect('/kategori/create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        $kategori->load('barang');
        $title = 'Detail Kategori';

        return view('pages.Kategori.detail-kategori', compact('kategori', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        $title = 'Edit Kategori';
        return view('pages.Kategori.edit-kategori', compact('kategori', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $newName = null; // inisialisasi $newName untuk penanganan gambar

        // // Proses upload gambar
        // if ($request->hasFile('image') && $request->file('image')->isValid()) {
        //     $newName = $request->nama . '-' . now()->timestamp . '.' . $request->file('image')->getClientOriginalExtension();
        //     $request->file('image')->storeAs('public/images', $newName);

        //     // Hapus gambar lama jika ada
        //     if ($lokasi->gambar && Storage::exists('public/images/' . $lokasi->gambar)) {
        //         Storage::delete('public/images/' . $lokasi->gambar);
        //     }
        // }

        $updated = $kategori->update([
            'nama' => $request->nama,
        ]);

        if ($updated) {
            session()->flash('status', 'success');
            session()->flash('message', 'Kategori berhasil diperbarui.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal memperbarui kategori. Silakan coba lagi.');
        }

        return redirect('/kategori/' . $kategori->id . '/edit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        $deleted = $kategori->delete();

        if ($deleted) {
            session()->flash('status', 'success');
            session()->flash('message', 'Kategori berhasil dihapus.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menghapus kategori. Silakan coba lagi.');
        }

        return redirect()->route('kategori.index');
    }
}