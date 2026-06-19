@extends('layouts.app')

@php
// Prepare chart data
$bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

// Status data
$statusBelumLunas = \App\Models\Billing::where('status_pembayaran', 'belum_lunas')->count();
$statusLunas = \App\Models\Billing::where('status_pembayaran', 'lunas')->count();
$statusBatal = \App\Models\Billing::where('status_pembayaran', 'batal')->count();

// Tarif data
$tarifLabels = [];
$tarifCounts = [];
foreach(\App\Models\TarifAir::all() as $tarif) {
$tarifLabels[] = $tarif->golongan;
$tarifCounts[] = \App\Models\Pelanggan::where('tarif_air_id', $tarif->id)->count();
}

// Get selected year for water usage chart (default to current year)
$selectedYear = request()->get('pemakaian_tahun', date('Y'));
$currentYear = date('Y');

// Get available years for filter (from 2025 to current year)
$availableYears = [];
for ($year = 2025; $year <= $currentYear; $year++) {
    $availableYears[]=$year;
    }

    // Pemakaian per bulan untuk tahun yang dipilih (12 bulan penuh)
    $pemakaianBulan=[];
    $pemakaianLabels=[];
    for ($bulan=1; $bulan <=12; $bulan++) {
    $periode=sprintf("%d-%02d", $selectedYear, $bulan);
    $total=\App\Models\Billing::where('periode', 'like' , "%$periode%" )->sum('pemakaian');
    $pemakaianLabels[] = $bulanList[$bulan - 1];
    $pemakaianBulan[] = $total;
    }

    // Tagihan vs Pembayaran per bulan (6 bulan terakhir)
    $tagihanPerBulan = [];
    $pembayaranPerBulan = [];
    $tagihanLabels = [];
    for ($i = 5; $i >= 0; $i--) {
    $bulan = date('m', strtotime("-$i months"));
    $tahun = date('Y', strtotime("-$i months"));
    $periode = "$tahun-$bulan";
    $tagihan = \App\Models\Billing::where('periode', 'like', "%$periode%")->sum('tagihan');
    $pembayaran = \App\Models\Billing::where('periode', 'like', "%$periode%")->where('status_pembayaran', 'lunas')->sum('tagihan');
    $tagihanPerBulan[] = $tagihan;
    $pembayaranPerBulan[] = $pembayaran;
    $tagihanLabels[] = $bulanList[(int)$bulan - 1];
    }
    @endphp

    @section('content')
    <div class="page-title">Dashboard</div>

    <!-- Summary Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 24px;">
        <!-- Total Pelanggan -->
        <div class="card" style="padding: 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="font-size: 13px; opacity: 0.9; margin-bottom: 8px;">Total Pelanggan</p>
                    <h3 style="font-size: 28px; font-weight: 700; margin: 0;">{{ \App\Models\Pelanggan::count() }}</h3>
                </div>
                <i class='bx bx-group' style="font-size: 32px; opacity: 0.8;"></i>
            </div>
        </div>

        <!-- Pelanggan Aktif -->
        <div class="card" style="padding: 24px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="font-size: 13px; opacity: 0.9; margin-bottom: 8px;">Pelanggan Aktif</p>
                    <h3 style="font-size: 28px; font-weight: 700; margin: 0;">{{ \App\Models\Pelanggan::where('status', 'aktif')->count() }}</h3>
                </div>
                <i class='bx bx-check-circle' style="font-size: 32px; opacity: 0.8;"></i>
            </div>
        </div>

        <!-- Total Tagihan -->
        <div class="card" style="padding: 24px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="font-size: 13px; opacity: 0.9; margin-bottom: 8px;">Total Tagihan</p>
                    <h3 style="font-size: 20px; font-weight: 700; margin: 0;">Rp {{ number_format(\App\Models\Billing::sum('total_tagihan'), 0, ',', '.') }}</h3>
                </div>
                <i class='bx bx-money' style="font-size: 32px; opacity: 0.8;"></i>
            </div>
        </div>

        <!-- Belum Lunas -->
        <div class="card" style="padding: 24px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="font-size: 13px; opacity: 0.9; margin-bottom: 8px;">Belum Lunas</p>
                    <h3 style="font-size: 20px; font-weight: 700; margin: 0;">Rp {{ number_format(\App\Models\Billing::where('status_pembayaran', 'belum_lunas')->sum('total_tagihan'), 0, ',', '.') }}</h3>
                </div>
                <i class='bx bx-time-five' style="font-size: 32px; opacity: 0.8;"></i>
            </div>
        </div>

        <!-- Sudah Lunas -->
        <div class="card" style="padding: 24px; background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); color: white; border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="font-size: 13px; opacity: 0.9; margin-bottom: 8px;">Sudah Lunas</p>
                    <h3 style="font-size: 20px; font-weight: 700; margin: 0;">Rp {{ number_format(\App\Models\Billing::where('status_pembayaran', 'lunas')->sum('total_tagihan'), 0, ',', '.') }}</h3>
                </div>
                <i class='bx bx-check-double' style="font-size: 32px; opacity: 0.8;"></i>
            </div>
        </div>

        <!-- Total Pemakaian -->
        <div class="card" style="padding: 24px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333; border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="font-size: 13px; opacity: 0.8; margin-bottom: 8px;">Total Pemakaian Air</p>
                    <h3 style="font-size: 28px; font-weight: 700; margin: 0;">{{ \App\Models\Billing::sum('pemakaian') }} <span style="font-size: 16px;">m³</span></h3>
                </div>
                <i class='bx bx-droplet' style="font-size: 32px; opacity: 0.6;"></i>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 24px;">
        <!-- Status Pembayaran Chart -->
        <div class="card" style="padding: 24px;">
            <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: var(--text-main);">Status Pembayaran</h4>
            <div style="position: relative; height: 300px;">
                <canvas id="chartStatusPembayaran"></canvas>
            </div>
        </div>

        <!-- Tarif Distribution Chart -->
        <div class="card" style="padding: 24px;">
            <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: var(--text-main);">Distribusi Tarif</h4>
            <div style="position: relative; height: 300px;">
                <canvas id="chartTarifDistribusi"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card" style="padding: 24px;">
            <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: var(--text-main);">Statistik Pemakaian</h4>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-muted); font-size: 14px;">Rata-rata Pemakaian</span>
                    <span style="font-weight: 600; color: var(--primary);">
                        @php
                        $avg = \App\Models\Billing::avg('pemakaian');
                        @endphp
                        {{ round($avg, 2) }} m³
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-muted); font-size: 14px;">Total Transaksi</span>
                    <span style="font-weight: 600; color: var(--primary);">
                        {{ \App\Models\Billing::count() }} tagihan
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-muted); font-size: 14px;">Tarif Air</span>
                    <span style="font-weight: 600; color: var(--primary);">
                        {{ \App\Models\TarifAir::count() }} jenis
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pemakaian Air Per Bulan Chart -->
    <div class="card" style="margin-bottom: 24px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h4 style="font-size: 16px; font-weight: 600; color: var(--text-main); margin: 0;">Pemakaian Air Per Bulan</h4>
            <div style="display: flex; gap: 10px; align-items: center;">
                <form method="GET" style="display: flex; gap: 10px; align-items: center;">
                    @foreach(request()->query() as $key => $value)
                    @if($key !== 'pemakaian_tahun')
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                    @endforeach
                    <label for="pemakaian_tahun" style="font-size: 14px; font-weight: 500; color: var(--text-main); margin: 0;">Tahun:</label>
                    <select id="pemakaian_tahun" name="pemakaian_tahun" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; cursor: pointer;" onchange="this.form.submit();">
                        @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        <div style="position: relative; height: 350px;">
            <canvas id="chartPemakaianBulanan"></canvas>
        </div>
    </div>

    <!-- Tagihan vs Pembayaran Chart -->
    <!-- <div class="card" style="margin-bottom: 24px; padding: 24px;">
    <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: var(--text-main);">Tagihan Terhadap Pembayaran</h4>
    <div style="position: relative; height: 350px;">
        <canvas id="chartTagihanPembayaran"></canvas>
    </div>
</div> -->

    <!-- Tables -->
    <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
        <!-- Tagihan Terbaru -->
        <div class="card">
            <h3 style="margin-bottom: 20px; font-size: 16px; font-weight: 600; color: var(--text-main);">Tagihan Terbaru</h3>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No Tagihan</th>
                            <th>Pelanggan</th>
                            <th>Periode</th>
                            <th>Pemakaian</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Billing::with(['pelanggan'])->latest()->take(10)->get() as $b)
                        <tr>
                            <td style="font-weight: 500;">{{ $b->no_invoice }}</td>
                            <td>
                                <div>{{ $b->pelanggan->nama ?? '-' }}</div>
                                <small style="color: var(--text-muted);">{{ $b->pelanggan->no_pelanggan ?? '-' }}</small>
                            </td>
                            <td>
                                @php
                                $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                [$tahun, $bulan] = explode('-', $b->periode);
                                $namaBulan = $bulanList[(int)$bulan - 1];
                                @endphp
                                {{ $namaBulan }} {{ $tahun }}
                            </td>
                            <td>{{ $b->pemakaian }} m³</td>
                            <td style="font-weight: 600;">Rp {{ number_format($b->total_tagihan, 0, ',', '.') }}</td>
                            <td>
                                @if($b->status_pembayaran == 'lunas')
                                <span class="badge badge-success">Lunas</span>
                                @elseif($b->status_pembayaran == 'batal')
                                <span class="badge badge-danger" style="background: #e5e7eb; color: #374151;">Batal</span>
                                @else
                                <span class="badge badge-warning" style="background: #fef3c7; color: #b45309;">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 32px;">Belum ada data tagihan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pelanggan Terbaru -->
        <div class="card">
            <h3 style="margin-bottom: 20px; font-size: 16px; font-weight: 600; color: var(--text-main);">Pelanggan Terbaru</h3>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No Pelanggan</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Tarif / Golongan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Pelanggan::with('tarifAir')->latest()->take(10)->get() as $p)
                        <tr>
                            <td style="font-weight: 500;">{{ $p->no_pelanggan }}</td>
                            <td>{{ $p->nama }}</td>
                            <td>{{ $p->alamat }}</td>
                            <td>{{ $p->tarifAir->golongan ?? '-' }} ({{ $p->tarifAir->kode ?? '-' }})</td>
                            <td>
                                @if($p->status == 'aktif')
                                <span class="badge badge-success">Aktif</span>
                                @else
                                <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">Belum ada data pelanggan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="module">
        /** @type {any} */

        import {
            initStatusPembayaranChart,
            initTarifDistribusiChart,
            initPemakaianBulananChart,
            initTagihanPembayaranChart
        } from '/js/dashboard-charts.js';

        document.addEventListener('DOMContentLoaded', function() {
            initStatusPembayaranChart(@json($statusBelumLunas), @json($statusLunas), @json($statusBatal));
            initTarifDistribusiChart(@json($tarifLabels), @json($tarifCounts));
            initPemakaianBulananChart(@json($pemakaianLabels), @json($pemakaianBulan));
            initTagihanPembayaranChart(@json($tagihanLabels), @json($tagihanPerBulan), @json($pembayaranPerBulan));
        });
    </script>
    @endsection