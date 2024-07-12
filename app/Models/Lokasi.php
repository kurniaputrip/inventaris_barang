<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function lokasi()
    {
        return $this->hasMany(Barang::class);
    }

    public function barang()
    {
        return $this->belongsTo(Lokasi::class);
    }
}

