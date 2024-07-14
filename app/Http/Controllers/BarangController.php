<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Kelola Barang';
        $barang = Barang::with('lokasi')->paginate(10);
        return view('pages.barang.kelola-barang', compact('title', 'barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lokasi = Lokasi::all();
        $kategori = Kategori::all();
        $title = 'Kelola Barang';
        return view('pages.barang.add-barang', compact('title', 'lokasi', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required|integer',
            'lokasi_id' => 'required|exists:lokasis,id', // pastikan lokasi_id ada dalam tabel lokasis
            'kategori_id' => 'required|array', // pastikan kategori_id adalah array
            'kategori_id.*' => 'exists:kategoris,id', // pastikan semua nilai dalam array kategori_id ada dalam tabel kategoris
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // validasi file gambar
        ]);

        $newName = null; // inisialisasi $newName untuk penanganan gambar

        // Proses upload gambar
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $newName = $request->nama . '-' . now()->timestamp . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images'), $newName); // simpan gambar ke direktori public/images
        }

        // Simpan data barang ke dalam tabel 'barangs'
        $barang = Barang::create([
            'nama' => $request->nama,
            'jumlah' => $request->jumlah,
            'stok' => $request->jumlah, // asumsi stok adalah sama dengan jumlah
            'gambar' => $newName, // simpan nama gambar jika ada
            'lokasi_id' => $request->lokasi_id,
        ]);

        $barang->kategori()->attach($request->kategori_id);

        if ($barang) {
            session()->flash('status', 'success');
            session()->flash('message', 'Produk berhasil ditambahkan.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menambahkan produk. Silakan coba lagi.');
        }

        return redirect('/barang/create');
    }


    public function show(Barang $barang)
    {
        $barang->load('kategori', 'lokasi');
        $title = 'Kelola Barang';

        // Ambil detail peminjaman, kemudian grup berdasarkan user_id dan hitung jumlahnya
        $detail = DetailPeminjaman::select('user_id', DB::raw('SUM(jumlah) as total_peminjaman'))
            ->where('barang_id', $barang->id)
            ->groupBy('user_id')
            ->get();

        return view('pages.Barang.detail-barang', compact('barang', 'title', 'detail'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $lokasi = Lokasi::all();
        $kategori = Kategori::all();
        $title = 'Kelola Barang';
        return view('pages.barang.edit-barang', compact('barang', 'title', 'lokasi', 'kategori'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required|integer',
            'lokasi_id' => 'required|exists:lokasis,id',
            'kategori_id' => 'required|array',
            'kategori_id.*' => 'exists:kategoris,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload and deletion
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Upload new image
            $newName = $request->nama . '-' . now()->timestamp . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('public/images', $newName);

            // Delete old image if exists
            if ($barang->gambar && Storage::exists('public/images/' . $barang->gambar)) {
                Storage::delete('public/images/' . $barang->gambar);
            }
        }

        // Update barang data
        $barang->nama = $request->nama;
        $barang->jumlah = $request->jumlah;
        $barang->stok = $request->jumlah; // assuming stok is the same as jumlah
        $barang->lokasi_id = $request->lokasi_id;

        if (isset($newName)) {
            $barang->gambar = $newName;
        }

        $barang->save();

        // Sync kategori
        $barang->kategori()->sync($request->kategori_id);

        if ($barang) {
            session()->flash('status', 'success');
            session()->flash('message', 'Produk berhasil diperbarui.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal memperbarui produk. Silakan coba lagi.');
        }

        return redirect('/barang/' . $barang->id . '/edit');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        // Hapus gambar jika ada
        if ($barang->gambar && file_exists(storage_path('app/public/images/' . $barang->gambar))) {
            unlink(storage_path('app/public/images/' . $barang->gambar));
        }

        // Hapus semua relasi kategori menggunakan detach()
        $barang->kategori()->detach();

        // Hapus barang dari database
        $deleted = $barang->delete();

        if ($deleted) {
            session()->flash('status', 'success');
            session()->flash('message', 'Produk berhasil dihapus.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menghapus produk. Silakan coba lagi.');
        }

        return redirect()->route('barang.index');
    }
}
