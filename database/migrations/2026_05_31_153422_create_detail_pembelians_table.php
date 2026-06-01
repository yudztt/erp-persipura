<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->id('id_detail_pembelian');
            $table->unsignedBigInteger('id_pembelian');
            $table->unsignedBigInteger('id_produk');
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelians')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pembelians');
    }
};
