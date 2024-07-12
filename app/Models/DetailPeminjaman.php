<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'detail_peminjaman';
    protected $fillable = [
        'barang_id',
        'peminjaman_id',
        'user_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
    ];

    /**
     * Get the barang associated with the detail peminjaman.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Get the peminjaman associated with the detail peminjaman.
     */
    public function peminjaman()
    {
        return $this->belongsTo(DetailPeminjaman::class);
    }

    /**
     * Get the user associated with the detail peminjaman.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
