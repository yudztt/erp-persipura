<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with(['supplier', 'user', 'detailPembelians.produk'])->get();

        return response()->json($pembelians);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_supplier'          => 'required|exists:suppliers,id_supplier',
            'id_user'              => 'required|exists:users,id_user',
            'tanggal'              => 'required|date',
            'details'              => 'required|array|min:1',
            'details.*.id_produk'  => 'required|exists:produks,id_produk',
            'details.*.qty'        => 'required|integer|min:1',
            'details.*.harga'      => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;

            foreach ($validated['details'] as $detail) {
                $detail['subtotal'] = $detail['qty'] * $detail['harga'];
                $total += $detail['subtotal'];
            }

            $pembelian = Pembelian::create([
                'id_supplier' => $validated['id_supplier'],
                'id_user'     => $validated['id_user'],
                'tanggal'     => $validated['tanggal'],
                'total'       => $total,
            ]);

            foreach ($validated['details'] as $detail) {
                DetailPembelian::create([
                    'id_pembelian' => $pembelian->id_pembelian,
                    'id_produk'    => $detail['id_produk'],
                    'qty'          => $detail['qty'],
                    'harga'        => $detail['harga'],
                    'subtotal'     => $detail['qty'] * $detail['harga'],
                ]);

                // Tambah stok produk
                Produk::where('id_produk', $detail['id_produk'])
                    ->increment('stok', $detail['qty']);
            }

            DB::commit();

            return response()->json($pembelian->load(['supplier', 'user', 'detailPembelians.produk']), 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Gagal menyimpan pembelian.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Pembelian $pembelian)
    {
        return response()->json($pembelian->load(['supplier', 'user', 'detailPembelians.produk']));
    }

    public function destroy(Pembelian $pembelian)
    {
        DB::beginTransaction();

        try {
            // Kembalikan stok produk
            foreach ($pembelian->detailPembelians as $detail) {
                Produk::where('id_produk', $detail->id_produk)
                    ->decrement('stok', $detail->qty);
            }

            $pembelian->delete();

            DB::commit();

            return response()->json(['message' => 'Pembelian berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Gagal menghapus pembelian.', 'error' => $e->getMessage()], 500);
        }
    }
}
