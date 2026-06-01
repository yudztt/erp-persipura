<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_kategori',
        'id_supplier',
        'kode_produk',
        'nama_produk',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelian::class, 'id_produk', 'id_produk');
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_produk', 'id_produk');
    }

    public function prediksiPenjualans()
    {
        return $this->hasMany(PrediksiPenjualan::class, 'id_produk', 'id_produk');
    }

    public function restockRekomendasis()
    {
        return $this->hasMany(RestockRekomendasi::class, 'id_produk', 'id_produk');
    }
}
