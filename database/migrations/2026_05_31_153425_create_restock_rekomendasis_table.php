<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restock_rekomendasis', function (Blueprint $table) {
            $table->id('id_restock');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_prediksi');
            $table->integer('stok_saat_ini');
            $table->integer('stok_disarankan');
            $table->string('status', 50);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
            $table->foreign('id_prediksi')->references('id_prediksi')->on('prediksi_penjualans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restock_rekomendasis');
    }
};
