<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    protected $primaryKey = 'id_detail_pembelian';

    protected $fillable = [
        'id_pembelian',
        'id_produk',
        'qty',
        'harga',
        'subtotal',
    ];

    protected $casts = [
        'harga'    => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
