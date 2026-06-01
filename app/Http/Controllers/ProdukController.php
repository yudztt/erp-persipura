<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with(['kategori', 'supplier'])->get();

        return response()->json($produks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori'  => 'required|exists:kategoris,id_kategori',
            'id_supplier'  => 'required|exists:suppliers,id_supplier',
            'kode_produk'  => 'required|string|max:50|unique:produks,kode_produk',
            'nama_produk'  => 'required|string|max:150',
            'harga_beli'   => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        $produk = Produk::create($validated);

        return response()->json($produk->load(['kategori', 'supplier']), 201);
    }

    public function show(Produk $produk)
    {
        return response()->json($produk->load(['kategori', 'supplier']));
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'id_kategori'  => 'sometimes|exists:kategoris,id_kategori',
            'id_supplier'  => 'sometimes|exists:suppliers,id_supplier',
            'kode_produk'  => 'sometimes|string|max:50|unique:produks,kode_produk,' . $produk->id_produk . ',id_produk',
            'nama_produk'  => 'sometimes|string|max:150',
            'harga_beli'   => 'sometimes|numeric|min:0',
            'harga_jual'   => 'sometimes|numeric|min:0',
            'stok'         => 'sometimes|integer|min:0',
            'stok_minimum' => 'sometimes|integer|min:0',
        ]);

        $produk->update($validated);

        return response()->json($produk->load(['kategori', 'supplier']));
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();

        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }
}
