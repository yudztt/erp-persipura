<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockRekomendasi extends Model
{
    protected $primaryKey = 'id_restock';

    protected $fillable = [
        'id_produk',
        'id_prediksi',
        'stok_saat_ini',
        'stok_disarankan',
        'status',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function prediksiPenjualan()
    {
        return $this->belongsTo(PrediksiPenjualan::class, 'id_prediksi', 'id_prediksi');
    }
}
