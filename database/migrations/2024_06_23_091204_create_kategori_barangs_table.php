<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang_kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel kategori_barang terlebih dahulu
        Schema::dropIfExists('barang_kategori');

        // Kemudian hapus tabel barangs
        Schema::dropIfExists('barangs');

        // Jika ada tabel lain yang terkait, pastikan dihapus juga dalam urutan yang benar
        Schema::dropIfExists('kategoris');
    }
};
