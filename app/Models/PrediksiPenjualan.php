<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrediksiPenjualan extends Model
{
    protected $primaryKey = 'id_prediksi';

    protected $fillable = [
        'id_produk',
        'bulan',
        'tahun',
        'hasil_prediksi',
        'tanggal_prediksi',
    ];

    protected $casts = [
        'hasil_prediksi'   => 'decimal:2',
        'tanggal_prediksi' => 'date',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function restockRekomendasis()
    {
        return $this->hasMany(RestockRekomendasi::class, 'id_prediksi', 'id_prediksi');
    }
}
