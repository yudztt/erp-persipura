<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'sales');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $data = [
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'totalRevenue' => 0,
            'unitsSold' => 0,
            'totalStockIn' => 0,
            'cogs' => 0,
            'margin' => 0,
            'reportData' => []
        ];

        if ($type === 'sales') {
            // Calculate total revenue from Penjualan
            $penjualan = Penjualan::whereBetween('tanggal', [$startDate, $endDate])->get();
            $data['totalRevenue'] = $penjualan->sum('total');

            // Get detail penjualan in date range
            $detailPenjualans = DetailPenjualan::whereHas('penjualan', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            })->with('produk.kategori')->get();

            $data['unitsSold'] = $detailPenjualans->sum('qty');

            // Calculate COGS (HPP): qty * harga_beli
            $data['cogs'] = $detailPenjualans->sum(function ($detail) {
                return $detail->qty * ($detail->produk->harga_beli ?? 0);
            });

            if ($data['totalRevenue'] > 0) {
                $data['margin'] = (($data['totalRevenue'] - $data['cogs']) / $data['totalRevenue']) * 100;
            }

            // Aggregate report data by product
            $reportData = [];
            foreach ($detailPenjualans as $detail) {
                $id = $detail->id_produk;
                if (!isset($reportData[$id])) {
                    $reportData[$id] = [
                        'produk' => $detail->produk->nama_produk ?? 'Unknown',
                        'kategori' => $detail->produk->kategori->nama_kategori ?? 'Unknown',
                        'unit_terjual' => 0,
                        'pendapatan' => 0,
                        'rata_rata_harga' => 0,
                        'sisa_stok' => $detail->produk->stok ?? 0,
                    ];
                }
                $reportData[$id]['unit_terjual'] += $detail->qty;
                $reportData[$id]['pendapatan'] += $detail->subtotal;
            }

            // Calculate average price
            foreach ($reportData as $key => $row) {
                if ($row['unit_terjual'] > 0) {
                    $reportData[$key]['rata_rata_harga'] = $row['pendapatan'] / $row['unit_terjual'];
                }
            }

            // Sort by revenue descending
            usort($reportData, function($a, $b) {
                return $b['pendapatan'] <=> $a['pendapatan'];
            });

            $data['reportData'] = $reportData;
            $data['reportTitle'] = 'Ringkasan Penjualan';

        } elseif ($type === 'inventory') {
            // Inventory Report
            $produks = Produk::with('kategori')->get();
            
            $data['totalStockIn'] = $produks->sum('stok'); 
            $data['totalRevenue'] = $produks->sum(function($p) { return $p->stok * $p->harga_jual; }); 
            $data['cogs'] = $produks->sum(function($p) { return $p->stok * $p->harga_beli; }); 

            if ($data['totalRevenue'] > 0) {
                $data['margin'] = (($data['totalRevenue'] - $data['cogs']) / $data['totalRevenue']) * 100;
            }

            $reportData = [];
            foreach ($produks as $produk) {
                $reportData[] = [
                    'produk' => $produk->nama_produk,
                    'kategori' => $produk->kategori->nama_kategori ?? 'Unknown',
                    'unit_terjual' => '-', 
                    'pendapatan' => $produk->stok * $produk->harga_jual, 
                    'rata_rata_harga' => $produk->harga_jual,
                    'sisa_stok' => $produk->stok,
                ];
            }
            $data['reportData'] = $reportData;
            $data['reportTitle'] = 'Ringkasan Inventaris';
        } else {
            $data['reportTitle'] = 'Ringkasan Lainnya';
        }

        if ($request->has('export') && $request->input('export') === 'pdf') {
            $pdf = Pdf::loadView('intelligence.reports_pdf', $data);
            return $pdf->download('Laporan_'.$type.'_'.$startDate.'_to_'.$endDate.'.pdf');
        }

        return view('intelligence.reports', $data);
    }
}
