<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['kategori', 'supplier'])->latest();
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('kode_produk', 'like', "%{$search}%");
        }
        
        $produks = $query->paginate(10)->appends($request->query());
        
        return view('inventory.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('inventory.create', compact('kategoris', 'suppliers'));
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

        Produk::create($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('inventory.edit', compact('produk', 'kategoris', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'id_kategori'  => 'required|exists:kategoris,id_kategori',
            'id_supplier'  => 'required|exists:suppliers,id_supplier',
            'kode_produk'  => 'required|string|max:50|unique:produks,kode_produk,' . $produk->id_produk . ',id_produk',
            'nama_produk'  => 'required|string|max:150',
            'harga_beli'   => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        $produk->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
