<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\DetailPeminjaman;

class ViewController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        $jumlahBarang = Barang::count();
        $jumlahKategori = Kategori::count();
        $jumlahLokasi = Lokasi::count();
        $jumlahPeminjam = DetailPeminjaman::whereNull('masuk')->count();

        return view('pages.dashboard', compact('title', 'jumlahBarang', 'jumlahKategori', 'jumlahLokasi', 'jumlahPeminjam'));
    }
}
