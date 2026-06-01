<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $primaryKey = 'id_supplier';

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'telepon',
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_supplier', 'id_supplier');
    }

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'id_supplier', 'id_supplier');
    }
}
