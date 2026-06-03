@extends('layouts.app')

@section('title', 'Prakiraan Penjualan - Persipura ERP')
@section('header_title', 'Prediksi Penjualan')
@section('header_breadcrumb', '/ Prediksi AI')

@section('content')
  <!-- Control Panel Card -->
  <div class="ai-card" style="margin-bottom: 24px;">
    <div class="ai-tag"><i class="ti ti-brain" style="font-size:12px"></i> Prakiraan Berbasis AI</div>
    <div class="ai-title">Prediksi Penjualan & Kebutuhan Stok</div>
    <div class="ai-sub">Didukung oleh Google Gemma 4 AI. Menganalisis riwayat transaksi penjualan riil (30, 60, dan 90 hari terakhir) serta tingkat persediaan saat ini.</div>
    
    <hr style="border-color: rgba(255,255,255,0.1); margin: 20px 0;">
    
    <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
      <div style="flex: 1; min-width: 250px;">
        <label for="productSelector" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: rgba(255,255,255,0.8);">Pilih Produk untuk Dianalisis:</label>
        <select id="productSelector" style="width: 100%; padding: 10px 14px; border-radius: 8px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: white; outline: none; font-size: 14px; cursor: pointer;">
          <option value="all" style="background: #1e293b; color: white;">⚡ Jalankan Prediksi untuk Top 5 Produk Kritis</option>
          @foreach($produks as $prod)
            <option value="{{ $prod->id_produk }}" style="background: #1e293b; color: white;">
              {{ $prod->nama_produk }} (Stok: {{ $prod->stok }} / Min: {{ $prod->stok_minimum }})
            </option>
          @endforeach
        </select>
      </div>
      <div>
        <button onclick="triggerAIForecast()" class="btn btn-primary" style="background: #e21c2c; border-color: #e21c2c; font-weight: 600; padding: 10px 20px; border-radius: 8px; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(226,28,44,0.3); transition: 0.2s;">
          <i class="ti ti-cpu" style="font-size: 18px;"></i> Jalankan Prediksi AI
        </button>
      </div>
    </div>
  </div>

  <!-- KPI Summaries -->
  <div class="kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <!-- KPI 1: Stok Rendah Kritis -->
    <div class="kpi-card" style="background: white; border: 1px solid var(--gray-200); border-radius: 12px; padding: 20px; position: relative; overflow: hidden; --accent: #C1121F;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div class="kpi-label" style="font-size: 11px; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: .5px;">Stok Rendah (Kritis)</div>
        <div style="width: 32px; height: 32px; border-radius: 8px; background: #FEE2E2; display: flex; align-items: center; justify-content: center; color: #C1121F;">
          <i class="ti ti-alert-triangle" style="font-size: 18px;"></i>
        </div>
      </div>
      <div class="kpi-value" style="font-size: 24px; font-weight: 700; color: var(--gray-900); margin: 4px 0;">{{ $kritisCount }} Produk</div>
      <div class="kpi-change" style="font-size: 12px; color: #991B1B; font-weight: 500; display: flex; align-items: center; gap: 4px; margin-top: 8px;">
        Memerlukan pengisian stok segera.
      </div>
    </div>
    
    <!-- KPI 2: Rekomendasi Restock -->
    <div class="kpi-card" style="background: white; border: 1px solid var(--gray-200); border-radius: 12px; padding: 20px; position: relative; overflow: hidden; --accent: #2563EB;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div class="kpi-label" style="font-size: 11px; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: .5px;">Rekomendasi Restock</div>
        <div style="width: 32px; height: 32px; border-radius: 8px; background: #DBEAFE; display: flex; align-items: center; justify-content: center; color: #2563EB;">
          <i class="ti ti-package" style="font-size: 18px;"></i>
        </div>
      </div>
      <div class="kpi-value" style="font-size: 24px; font-weight: 700; color: var(--gray-900); margin: 4px 0;">{{ number_format($totalRestockUnits, 0) }} Unit</div>
      <div class="kpi-change" style="font-size: 12px; color: #1D4ED8; font-weight: 500; display: flex; align-items: center; gap: 4px; margin-top: 8px;">
        Volume pemesanan yang disarankan AI.
      </div>
    </div>
  </div>

  <!-- Breathtaking SVG Charts -->
  <div class="charts-row" style="margin-bottom: 24px; display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
    <!-- Chart 1: Proyeksi Permintaan Top 5 Produk -->
    <div class="chart-card" style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 18px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
      <div class="section-title" style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Proyeksi Permintaan Terlaris</div>
      <div class="section-subtitle" style="font-size: 12px; color: #64748b; margin-bottom: 20px;">Top 5 produk dengan jumlah prediksi penjualan bulan depan tertinggi</div>
      
      <div class="chart-svg-container" style="position: relative; height: 200px;">
        @php
          $topCharts = $allPrediksis->sortByDesc('hasil_prediksi')->take(5);
          $maxPredValue = max(50, $topCharts->max('hasil_prediksi') ?? 10);
        @endphp
        
        @if($topCharts->isEmpty())
          <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#94a3b8;font-size:13px;flex-direction:column;gap:8px;">
            <i class="ti ti-chart-bar" style="font-size:40px;opacity:0.4"></i>
            Belum ada data prediksi untuk dirender ke grafik.
          </div>
        @else
          <div style="display: flex; flex-direction: column; gap: 16px; justify-content: center; height: 100%;">
            @foreach($topCharts as $index => $pred)
              @php
                $percentage = ($pred->hasil_prediksi / $maxPredValue) * 100;
              @endphp
              <div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 4px;">
                  <span>{{ Str::limit($pred->produk->nama_produk, 35) }}</span>
                  <span style="color: #e21c2c;">{{ number_format($pred->hasil_prediksi, 0) }} unit</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                  <div style="width: {{ $percentage }}%; height: 100%; background: linear-gradient(90deg, #f87171, #e21c2c); border-radius: 4px;"></div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    <!-- Chart 2: Analisis Rekomendasi Persediaan -->
    <div class="chart-card" style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 18px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
      <div class="section-title" style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Rasio Rekomendasi Restock</div>
      <div class="section-subtitle" style="font-size: 12px; color: #64748b; margin-bottom: 20px;">Proporsi status tingkat stok saat ini berdasarkan analisa AI</div>
      
      @php
        $stokBanyakCount = 0;
        $stokSedangCount = 0;
        $stokRendahCount = 0;
        
        foreach ($allPrediksis as $pred) {
            $rec = $pred->restockRekomendasis->first();
            if ($rec) {
                if ($rec->status === 'stok banyak') $stokBanyakCount++;
                elseif ($rec->status === 'stok sedang') $stokSedangCount++;
                elseif ($rec->status === 'stok rendah') $stokRendahCount++;
            }
        }
        $totalStatuses = $stokBanyakCount + $stokSedangCount + $stokRendahCount;
      @endphp
      
      <div class="chart-svg-container" style="display: flex; align-items: center; justify-content: center; height: 200px; gap: 24px;">
        @if($totalStatuses === 0)
          <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#94a3b8;font-size:13px;flex-direction:column;gap:8px;">
            <i class="ti ti-chart-pie" style="font-size:40px;opacity:0.4"></i>
            Jalankan prediksi AI untuk melihat persentase rasio status stok.
          </div>
        @else
          <!-- Graphical percentage indicators -->
          <div style="flex: 1; display: flex; flex-direction: column; gap: 14px;">
            @php
              $pctBanyak = ($stokBanyakCount / $totalStatuses) * 100;
              $pctSedang = ($stokSedangCount / $totalStatuses) * 100;
              $pctRendah = ($stokRendahCount / $totalStatuses) * 100;
            @endphp
            <div>
              <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">
                <span style="display: flex; align-items: center; gap: 6px;"><span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981; display: inline-block;"></span>Stok Banyak</span>
                <span>{{ number_format($pctBanyak, 1) }}% ({{ $stokBanyakCount }} item)</span>
              </div>
              <div style="width: 100%; height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden;">
                <div style="width: {{ $pctBanyak }}%; height: 100%; background: #10b981; border-radius: 3px;"></div>
              </div>
            </div>
            <div>
              <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">
                <span style="display: flex; align-items: center; gap: 6px;"><span style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>Stok Sedang</span>
                <span>{{ number_format($pctSedang, 1) }}% ({{ $stokSedangCount }} item)</span>
              </div>
              <div style="width: 100%; height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden;">
                <div style="width: {{ $pctSedang }}%; height: 100%; background: #f59e0b; border-radius: 3px;"></div>
              </div>
            </div>
            <div>
              <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">
                <span style="display: flex; align-items: center; gap: 6px;"><span style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444; display: inline-block;"></span>Stok Rendah</span>
                <span>{{ number_format($pctRendah, 1) }}% ({{ $stokRendahCount }} item)</span>
              </div>
              <div style="width: 100%; height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden;">
                <div style="width: {{ $pctRendah }}%; height: 100%; background: #ef4444; border-radius: 3px;"></div>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Active Filter Alert Banner -->
  @if($selectedProductId && $selectedProductId !== 'all')
    @php
      $filteredProdName = $produks->firstWhere('id_produk', $selectedProductId)->nama_produk ?? 'produk terpilih';
    @endphp
    <div style="background: #f1f5f9; padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #475569; border: 1px solid #e2e8f0; font-weight: 500;">
      <span style="display: flex; align-items: center; gap: 8px;"><i class="ti ti-filter" style="color: #e21c2c; font-size: 16px;"></i> Menampilkan prediksi untuk <strong>{{ $filteredProdName }}</strong>.</span>
      <a href="{{ route('intelligence.forecast') }}" style="color: #e21c2c; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="ti ti-refresh"></i> Tampilkan Semua Produk</a>
    </div>
  @endif

  <!-- Detailed Prediction Table -->
  <div class="table-card" style="background: white; border-radius: 12px; box-shadow: 0 4px 18px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; overflow: hidden;">
    <div class="table-header" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; gap: 12px;">
      <div>
        <div class="section-title" style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Riwayat & Detail Prediksi AI</div>
        <div class="section-subtitle" style="font-size: 12px; color: #64748b;">Hasil kalkulasi komprehensif untuk estimasi 30 hari ke depan</div>
      </div>
    </div>
    
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
        <thead>
          <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 600;">
            <th style="padding: 14px 18px; width: 30%;">Produk / SKU</th>
            <th style="padding: 14px 18px; width: 20%;">Stok Saat Ini / Min</th>
            <th style="padding: 14px 18px; width: 20%;">Status Tingkat Stok</th>
            <th style="padding: 14px 18px; width: 20%;">Disarankan Restock</th>
            <th style="padding: 14px 18px; width: 10%;">Waktu Analisa</th>
          </tr>
        </thead>
        <tbody id="predictionTableBody">
          @forelse($prediksis as $pred)
            @php
              $rec = $pred->restockRekomendasis->first();
              $status = $rec ? $rec->status : 'stok sedang';
              $stokDisarankan = $rec ? $rec->stok_disarankan : 0;
              
              // Custom styles matching the statuses
              $badgeClass = '';
              $badgeLabel = '';
              if ($status === 'stok banyak') {
                  $badgeClass = 'background-color: #d1fae5; color: #065f46; border: 1.5px solid #34d399;';
                  $badgeLabel = 'Stok Banyak';
              } elseif ($status === 'stok sedang') {
                  $badgeClass = 'background-color: #fef3c7; color: #92400e; border: 1.5px solid #fcd34d;';
                  $badgeLabel = 'Stok Sedang';
              } else {
                  $badgeClass = 'background-color: #fee2e2; color: #991b1b; border: 1.5px solid #fca5a5;';
                  $badgeLabel = 'Stok Rendah';
              }
            @endphp
            <tr style="border-bottom: 1px solid #f1f5f9; color: #334155;" class="forecast-row-item">
              <td style="padding: 14px 18px;">
                <div style="font-weight: 700; color: #1e293b; font-size: 14px;">{{ $pred->produk->nama_produk ?? '-' }}</div>
                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">SKU: {{ $pred->produk->kode_produk ?? '-' }} | Kategori: {{ $pred->produk->kategori->nama_kategori ?? 'Umum' }}</div>
              </td>
              <td style="padding: 14px 18px;">
                <div style="font-weight: 700; color: #1e293b;">{{ $pred->produk->stok ?? 0 }} unit</div>
                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Batas Minimum: {{ $pred->produk->stok_minimum ?? 0 }} unit</div>
              </td>
              <td style="padding: 14px 18px;">
                <span style="display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase; {{ $badgeClass }}">
                  {{ $badgeLabel }}
                </span>
              </td>
              <td style="padding: 14px 18px;">
                @if($stokDisarankan > 0)
                  <span style="display: inline-block; background-color: #fffbeb; color: #d97706; padding: 4px 12px; border-radius: 6px; font-weight: 800; border: 1px solid #fde68a; font-size: 13px;">
                    +{{ $stokDisarankan }} Unit
                  </span>
                @else
                  <span style="color: #64748b; font-style: italic; font-weight: 500;">Aman (0 Unit)</span>
                @endif
              </td>
              <td style="padding: 14px 18px; color: #64748b; font-size: 12px;">
                {{ $pred->tanggal_prediksi->format('d M Y') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="padding: 30px; text-align: center; color: #94a3b8; font-style: italic;">
                <div style="display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;">
                  <i class="ti ti-activity" style="font-size:40px;opacity:0.4"></i>
                  Belum ada riwayat prakiraan stok AI. Silakan jalankan kalkulasi prediksi AI di atas!
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
      {{ $prediksis->links() }}
    </div>
  </div>

  <!-- Loading Animation Overlay -->
  <div id="aiLoadingOverlay" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 10000; opacity: 0; pointer-events: none; transition: opacity 0.3s ease;">
    <div style="text-align: center; color: white; background: #1e293b; padding: 40px; border-radius: 20px; width: 90%; max-width: 450px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 40px rgba(0,0,0,0.5);">
      <div style="position: relative; width: 80px; height: 80px; margin: 0 auto 24px;">
        <div style="width: 100%; height: 100%; border: 4px solid rgba(226,28,44,0.15); border-radius: 50%; position: absolute;"></div>
        <div style="width: 100%; height: 100%; border: 4px solid transparent; border-top-color: #e21c2c; border-radius: 50%; position: absolute; animation: spin 1s linear infinite;"></div>
        <i class="ti ti-brain" style="font-size: 32px; color: #f87171; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
      </div>
      <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 10px; letter-spacing: 0.5px;">MENGHUBUNGI ASISTEN AI...</h3>
      <p id="aiLoadingText" style="font-size: 13px; color: #94a3b8; font-weight: 500; line-height: 1.5; min-height: 40px;">Mengumpulkan riwayat transaksi penjualan riil dari database...</p>
      
      <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden; margin-top: 24px;">
        <div id="aiProgressBar" style="width: 0%; height: 100%; background: #e21c2c; border-radius: 3px; transition: width 0.4s ease;"></div>
      </div>
    </div>
  </div>



  <script>
    function filterProduct(productId) {
        window.location.href = "{{ route('intelligence.forecast') }}?product_id=" + productId;
    }

    async function triggerAIForecast() {
        const productSelector = document.getElementById('productSelector');
        const productId = productSelector.value;
        
        const overlay = document.getElementById('aiLoadingOverlay');
        const loadingText = document.getElementById('aiLoadingText');
        const progressBar = document.getElementById('aiProgressBar');

        // Show premium loading state
        overlay.style.opacity = '1';
        overlay.style.pointerEvents = 'all';

        // Stage 1: Load database
        progressBar.style.width = '20%';
        loadingText.innerText = 'Mengumpulkan riwayat transaksi penjualan riil dari database...';
        
        await new Promise(r => setTimeout(r, 1200));

        // Stage 2: Aggregate values
        progressBar.style.width = '45%';
        loadingText.innerText = 'Mengagregasikan total pengeluaran stok 30, 60, dan 90 hari terakhir...';
        
        await new Promise(r => setTimeout(r, 1200));

        // Stage 3: Send to OpenRouter AI
        progressBar.style.width = '75%';
        loadingText.innerText = 'Menghubungkan ke Google Gemma 4 AI untuk menjalankan regresi tren stok...';

        try {
            const response = await fetch("{{ route('intelligence.forecast.generate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id_produk: productId })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                progressBar.style.width = '100%';
                loadingText.innerText = 'Sukses! Menyimpan hasil kalkulasi AI baru ke database ERP...';
                await new Promise(r => setTimeout(r, 1000));
                
                // Meredireksi ke produk yang baru dianalisis agar tabel otomatis tersaring dan terupdate
                window.location.href = "{{ route('intelligence.forecast') }}?product_id=" + productId;
            } else {
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
                progressBar.style.width = '0%';
                alert('Gagal memproses prediksi AI: ' + (data.error || 'Server error'));
            }
        } catch (error) {
            overlay.style.opacity = '0';
            overlay.style.pointerEvents = 'none';
            progressBar.style.width = '0%';
            alert('Gagal terhubung ke server ERP: ' + error.message);
        }
    }
  </script>
@endsection