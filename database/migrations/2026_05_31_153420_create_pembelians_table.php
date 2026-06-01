<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id('id_pembelian');
            $table->unsignedBigInteger('id_supplier');
            $table->unsignedBigInteger('id_user');
            $table->date('tanggal');
            $table->decimal('total', 15, 2);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_supplier')->references('id_supplier')->on('suppliers')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
