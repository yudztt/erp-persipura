<?php

namespace App\Http\Controllers;

use App\Models\PrediksiPenjualan;
use Illuminate\Http\Request;

class PrediksiPenjualanController extends Controller
{
    public function index()
    {
        $prediksis = PrediksiPenjualan::with('produk')->get();

        return response()->json($prediksis);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk'        => 'required|exists:produks,id_produk',
            'bulan'            => 'required|integer|min:1|max:12',
            'tahun'            => 'required|integer|min:2000',
            'hasil_prediksi'   => 'required|numeric|min:0',
            'tanggal_prediksi' => 'required|date',
        ]);

        $prediksi = PrediksiPenjualan::create($validated);

        return response()->json($prediksi->load('produk'), 201);
    }

    public function show(PrediksiPenjualan $prediksiPenjualan)
    {
        return response()->json($prediksiPenjualan->load(['produk', 'restockRekomendasis']));
    }

    public function update(Request $request, PrediksiPenjualan $prediksiPenjualan)
    {
        $validated = $request->validate([
            'id_produk'        => 'sometimes|exists:produks,id_produk',
            'bulan'            => 'sometimes|integer|min:1|max:12',
            'tahun'            => 'sometimes|integer|min:2000',
            'hasil_prediksi'   => 'sometimes|numeric|min:0',
            'tanggal_prediksi' => 'sometimes|date',
        ]);

        $prediksiPenjualan->update($validated);

        return response()->json($prediksiPenjualan->load('produk'));
    }

    public function destroy(PrediksiPenjualan $prediksiPenjualan)
    {
        $prediksiPenjualan->delete();

        return response()->json(['message' => 'Prediksi penjualan berhasil dihapus.']);
    }
}
