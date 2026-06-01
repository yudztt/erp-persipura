<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with(['customer', 'user', 'detailPenjualans.produk'])->get();

        return response()->json($penjualans);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_customer'          => 'required|exists:customers,id_customer',
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

            // Validasi stok sebelum transaksi
            foreach ($validated['details'] as $detail) {
                $produk = Produk::findOrFail($detail['id_produk']);

                if ($produk->stok < $detail['qty']) {
                    DB::rollBack();

                    return response()->json([
                        'message' => "Stok produk '{$produk->nama_produk}' tidak mencukupi. Stok tersedia: {$produk->stok}.",
                    ], 422);
                }

                $detail['subtotal'] = $detail['qty'] * $detail['harga'];
                $total += $detail['subtotal'];
            }

            $penjualan = Penjualan::create([
                'id_customer' => $validated['id_customer'],
                'id_user'     => $validated['id_user'],
                'tanggal'     => $validated['tanggal'],
                'total'       => $total,
            ]);

            foreach ($validated['details'] as $detail) {
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_produk'    => $detail['id_produk'],
                    'qty'          => $detail['qty'],
                    'harga'        => $detail['harga'],
                    'subtotal'     => $detail['qty'] * $detail['harga'],
                ]);

                // Kurangi stok produk
                Produk::where('id_produk', $detail['id_produk'])
                    ->decrement('stok', $detail['qty']);
            }

            DB::commit();

            return response()->json($penjualan->load(['customer', 'user', 'detailPenjualans.produk']), 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Gagal menyimpan penjualan.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Penjualan $penjualan)
    {
        return response()->json($penjualan->load(['customer', 'user', 'detailPenjualans.produk']));
    }

    public function destroy(Penjualan $penjualan)
    {
        DB::beginTransaction();

        try {
            // Kembalikan stok produk
            foreach ($penjualan->detailPenjualans as $detail) {
                Produk::where('id_produk', $detail->id_produk)
                    ->increment('stok', $detail->qty);
            }

            $penjualan->delete();

            DB::commit();

            return response()->json(['message' => 'Penjualan berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Gagal menghapus penjualan.', 'error' => $e->getMessage()], 500);
        }
    }
}
