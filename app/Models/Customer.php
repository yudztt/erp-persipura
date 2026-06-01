<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'id_customer';

    protected $fillable = [
        'nama_customer',
        'telepon',
        'email',
        'alamat',
    ];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'id_customer', 'id_customer');
    }
}
