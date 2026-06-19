@extends('layouts.app')

@section('content')
<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
    <a href="{{ route('billings.index') }}" class="btn btn-primary btn-sm" style="background: var(--body-bg); color: var(--text-main);"><i class='bx bx-arrow-back'></i> Kembali</a>
    <h2 class="page-title" style="margin-bottom: 0;">Update Status Tagihan</h2>
</div>

<div class="card" style="max-width: 600px;">

    <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 24px; border: 1px solid var(--border-color);">
        <h4 style="margin-bottom: 12px; font-weight: 600;">Rincian Tagihan</h4>
        <div style="display: grid; grid-template-columns: 120px 1fr; gap: 8px; font-size: 14px;">
            <div style="color: var(--text-muted);">Pelanggan</div>
            <div style="font-weight: 500;">{{ $billing->pelanggan->nama ?? '-' }} ({{ $billing->pelanggan->no_pelanggan ?? '-' }})</div>

            <div style="color: var(--text-muted);">Periode</div>
            <div style="font-weight: 500;">
                @php
                $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                [$tahun, $bulan] = explode('-', $billing->periode);
                $namaBulan = $bulanList[(int)$bulan - 1];
                @endphp
                {{ $namaBulan }} {{ $tahun }}
            </div>

            <div style="color: var(--text-muted);">Meteran</div>
            <div style="font-weight: 500;">Awal: {{ $billing->meter_awal }}, Akhir: {{ $billing->meter_akhir }}</div>

            <div style="color: var(--text-muted);">Pemakaian</div>
            <div style="font-weight: 500;">{{ $billing->pemakaian }} m³</div>

            <div style="color: var(--text-muted); margin-top: 8px;">Total Tagihan</div>
            <div style="font-weight: 700; font-size: 18px; color: var(--primary); margin-top: 8px;">Rp {{ number_format($billing->total_tagihan, 0, ',', '.') }}</div>
        </div>
    </div>

    <form action="{{ route('billings.update', $billing->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Status Pembayaran</label>
            <select name="status_pembayaran" class="form-control" required>
                <option value="belum_lunas" {{ $billing->status_pembayaran == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                <option value="lunas" {{ $billing->status_pembayaran == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="batal" {{ $billing->status_pembayaran == 'batal' ? 'selected' : '' }}>Batal (Void)</option>
            </select>
        </div>

        <div style="margin-top: 24px; text-align: right;">
            <button type="submit" class="btn btn-primary">Simpan Status</button>
        </div>
    </form>
</div>
@endsection