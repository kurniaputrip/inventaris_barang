<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\DetailPeminjaman;

class PeminjamanController extends Controller
{
    public function index()
    {
        $title = 'Peminjaman Barang';
        $barangs = Barang::get();
        return view('pages.PeminjamanBarang.peminjaman', compact('title', 'barangs'));
    }
    public function pinjam(Request $request)
    {
        // dd($request);
        // Cari barang berdasarkan ID atau kembalikan error 404 jika tidak ditemukan
        $barangValidate = Barang::findOrFail($request->barang_id);

        // Validasi input
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'keluar' => 'required|date',
            'jumlah' => [
                'required',
                'integer',
                'min:1',
                // Maksimum jumlah yang dapat dipinjam adalah stok barang yang tersedia
                'max:' . $barangValidate->stok,
            ],
            'barang_id' => 'required|exists:barangs,id', // Pastikan tabel barang adalah 'barangs'
        ]);

        // Simpan detail peminjaman
        $detailPeminjaman = new DetailPeminjaman();
        // $detailPeminjaman->nama = $validatedData['nama'];
        $detailPeminjaman->user_id = $validatedData['user_id'];
        $detailPeminjaman->keluar = $validatedData['keluar'];
        $detailPeminjaman->jumlah = $validatedData['jumlah'];
        $detailPeminjaman->barang_id = $validatedData['barang_id'];
        $detailPeminjaman->save();

        // Kurangi stok barang
        $barang = Barang::find($validatedData['barang_id']);
        if ($barang) {
            $barang->stok -= $validatedData['jumlah'];
            $barang->save();
        }

        if ($barang && $detailPeminjaman) {
            session()->flash('status', 'success');
            session()->flash('message', 'Berhasil Meminjam Barang.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal Meminjam.');
        }

        // Redirect atau tampilkan pesan berhasil
        return redirect('/peminjaman');
    }

    public function detail()
    {
        $title = 'Detail Peminjaman';
        $details = DetailPeminjaman::where('user_id', auth()->id())
            ->with('barang')
            ->paginate(12); // Sesuaikan dengan jumlah data per halaman yang diinginkan

        return view('pages.peminjamanBarang.detailPeminjaman', compact('title', 'details'));
    }


    public function kembali($id)
    {
        $detailPeminjaman = DetailPeminjaman::findOrFail($id);

        // Tambahkan jumlah barang yang dikembalikan ke stok barang
        $barang = Barang::findOrFail($detailPeminjaman->barang_id);
        $barang->stok += $detailPeminjaman->jumlah;
        $barang->save();

        // Set tanggal masuk menjadi waktu saat ini
        $detailPeminjaman->masuk = Carbon::now();
        $detailPeminjaman->save();

        // Set session flash message
        session()->flash('status', 'success');
        session()->flash('message', 'Berhasil Mengembalikan Barang.');

        return redirect()->back();
    }

    public function log()
    {
        $title = 'Log Peminjaman';
        $logs = DetailPeminjaman::with(['barang', 'user'])->orderBy('keluar', 'desc')->paginate(10);
        return view('pages.peminjamanBarang.logs', compact('logs', 'title'));
    }
}
