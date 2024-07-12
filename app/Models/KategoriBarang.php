<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;
    protected $guarded = [];
    Protected $table = 'kategori_barangs';

    public function kategoriBarang()
    {
        return $this->hasMany(KategoriBarang::class);
    }

    public function barangs()
    {
        return $this->belongsTo(Barang::class);
    }
}



