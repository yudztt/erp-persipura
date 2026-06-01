<?php

namespace App\Http\Controllers;

use App\Models\RestockRekomendasi;
use Illuminate\Http\Request;

class RestockRekomendasiController extends Controller
{
    public function index()
    {
        $restocks = RestockRekomendasi::with(['produk', 'prediksiPenjualan'])->get();

        return response()->json($restocks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk'       => 'required|exists:produks,id_produk',
            'id_prediksi'     => 'required|exists:prediksi_penjualans,id_prediksi',
            'stok_saat_ini'   => 'required|integer|min:0',
            'stok_disarankan' => 'required|integer|min:0',
            'status'          => 'required|string|max:50',
        ]);

        $restock = RestockRekomendasi::create($validated);

        return response()->json($restock->load(['produk', 'prediksiPenjualan']), 201);
    }

    public function show(RestockRekomendasi $restockRekomendasi)
    {
        return response()->json($restockRekomendasi->load(['produk', 'prediksiPenjualan']));
    }

    public function update(Request $request, RestockRekomendasi $restockRekomendasi)
    {
        $validated = $request->validate([
            'id_produk'       => 'sometimes|exists:produks,id_produk',
            'id_prediksi'     => 'sometimes|exists:prediksi_penjualans,id_prediksi',
            'stok_saat_ini'   => 'sometimes|integer|min:0',
            'stok_disarankan' => 'sometimes|integer|min:0',
            'status'          => 'sometimes|string|max:50',
        ]);

        $restockRekomendasi->update($validated);

        return response()->json($restockRekomendasi->load(['produk', 'prediksiPenjualan']));
    }

    public function destroy(RestockRekomendasi $restockRekomendasi)
    {
        $restockRekomendasi->delete();

        return response()->json(['message' => 'Restock rekomendasi berhasil dihapus.']);
    }
}
