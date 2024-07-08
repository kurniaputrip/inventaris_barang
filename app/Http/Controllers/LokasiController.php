<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $title = 'Kelola Lokasi';
        $lokasi = Lokasi::with('lokasi')->paginate(10);
        return view('pages.lokasi.kelola-lokasi', compact('title', 'lokasi'));
    }

    public function create()
    {
        $lokasi = Lokasi::all();
        $title = 'Kelola Lokasi';
        return view('pages.lokasi.add-lokasi', compact('title', 'lokasi'));
    }
}
