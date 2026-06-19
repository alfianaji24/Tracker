@extends('layouts.app')

@section('content')
<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
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

            <div style="color: var(--text-muted);">Tagihan Air</div>
            <div style="font-weight: 500;">Rp <span id="tagihan_air_display">{{ number_format($billing->tagihan_air, 0, ',', '.') }}</span></div>

            <div style="color: var(--text-muted);">Abonemen</div>
            <div style="font-weight: 500;">Rp <span id="abonemen_display">{{ number_format($billing->abonemen, 0, ',', '.') }}</span></div>

            <div style="color: var(--text-muted);">Subtotal</div>
            <div style="font-weight: 600;">Rp <span id="subtotal_display">{{ number_format($billing->tagihan_air + $billing->abonemen, 0, ',', '.') }}</span></div>

            <div style="color: var(--text-muted);">Diskon</div>
            <div style="font-weight: 500; color: #ef4444;">- Rp <span id="diskon_display">{{ number_format($billing->diskon ?? 0, 0, ',', '.') }}</span></div>

            <div style="color: var(--text-muted); padding-top: 12px; border-top: 2px solid #e5e7eb; margin-top: 12px;">Total Tagihan</div>
            <div style="font-weight: 700; font-size: 18px; color: var(--primary); padding-top: 12px; border-top: 2px solid #e5e7eb; margin-top: 12px;">Rp <span id="total_display">{{ number_format($billing->total_tagihan - ($billing->diskon ?? 0), 0, ',', '.') }}</span></div>
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

        <div class="form-group">
            <label class="form-label">Diskon (Opsional)</label>
            <input type="number" name="diskon" class="form-control" placeholder="Masukkan jumlah diskon dalam Rp" min="0" step="any" value="{{ $billing->diskon ?? 0 }}">
            <small style="color: #999; display: block; margin-top: 6px;">Opsional - bisa diisi tentatif</small>
        </div>

        <div style="margin-top: 24px; text-align: right;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
    // Data awal dari database
    const tagihanAir = {
        {
            $billing - > tagihan_air
        }
    };
    const abonemen = {
        {
            $billing - > abonemen
        }
    };
    const subtotal = tagihanAir + abonemen;
    const diskonInput = document.querySelector('input[name="diskon"]');

    function formatCurrency(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateCalculation() {
        const diskon = parseInt(diskonInput.value) || 0;
        const totalSetelahDiskon = Math.max(0, subtotal - diskon);

        // Update display
        document.getElementById('subtotal_display').textContent = formatCurrency(subtotal);
        document.getElementById('diskon_display').textContent = formatCurrency(diskon);
        document.getElementById('total_display').textContent = formatCurrency(totalSetelahDiskon);
    }

    // Event listener untuk perubahan diskon
    diskonInput.addEventListener('input', updateCalculation);
    diskonInput.addEventListener('change', updateCalculation);

    // Initialize saat page load
    document.addEventListener('DOMContentLoaded', updateCalculation);
</script>
@endsection