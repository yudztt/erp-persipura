<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediksi_penjualans', function (Blueprint $table) {
            $table->id('id_prediksi');
            $table->unsignedBigInteger('id_produk');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->decimal('hasil_prediksi', 15, 2);
            $table->date('tanggal_prediksi');
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediksi_penjualans');
    }
};
