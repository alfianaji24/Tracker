@extends('layouts.app')

@section('content')
<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
    <h2 class="page-title" style="margin-bottom: 0;">Buat Tagihan Baru</h2>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ route('billings.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label">Pelanggan Aktif</label>
            <select name="pelanggan_id" id="pelanggan_id" class="form-control" required onchange="loadMeterAwal()">
                <option value="">-- Pilih Pelanggan --</option>
                @foreach($pelanggans as $p)
                <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->no_pelanggan }} - {{ $p->nama }}
                </option>
                @endforeach
            </select>
            @error('pelanggan_id') <span style="color: var(--danger); font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Bulan Tagihan</label>
            <select name="periode_bulan" class="form-control" required>
                <option value="">-- Pilih Bulan --</option>
                @foreach($bulanList as $num => $nama)
                <option value="{{ $num }}" {{ old('periode_bulan', date('n')) == $num ? 'selected' : '' }}>
                    {{ $nama }}
                </option>
                @endforeach
            </select>
            @error('periode_bulan') <span style="color: var(--danger); font-size: 12px;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">No Invoice</label>
            <input type="text" id="no_invoice" class="form-control" readonly style="background-color: #f3f4f6; cursor: not-allowed;">
            <small style="color: var(--text-muted);">*Auto-generate format INV-YYYY/MM/NNN</small>
        </div>

        <!-- Hidden input untuk tahun (auto fill ke tahun sekarang) -->
        <input type="hidden" name="periode_tahun" value="{{ old('periode_tahun', date('Y')) }}">

        <hr style="margin: 24px 0; border: none; border-top: 1px solid var(--border-color);">
        <h4 style="margin-bottom: 16px;">Data Meteran Air</h4>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Angka Meter Awal (Bulan Lalu)</label>
                <input type="number" name="meter_awal" id="meter_awal" class="form-control" value="{{ old('meter_awal', 0) }}" required min="0" readonly style="background-color: #f3f4f6; cursor: not-allowed;">
                <small style="color: var(--text-muted);">*Auto-diambil dari meter akhir bulan lalu. Jika tidak ada, akan 0.</small>
            </div>
            <div class="form-group">
                <label class="form-label">Angka Meter Akhir (Bulan Ini)</label>
                <input type="number" name="meter_akhir" id="meter_akhir" class="form-control" value="{{ old('meter_akhir', 0) }}" required min="0" oninput="calculatePemakaian()">
            </div>
        </div>

        <div class="form-group" style="background: #f8fafc; padding: 16px; border-radius: 8px; margin-top: 8px;">
            <label class="form-label">Estimasi Pemakaian (m³)</label>
            <div id="estimasi_pemakaian" style="font-size: 24px; font-weight: 700; color: var(--primary);">0</div>
        </div>

        <div class="form-group" style="background: #f0fdf4; padding: 16px; border-radius: 8px; margin-top: 12px; border: 1px solid #86efac;">
            <label class="form-label">Perhitungan Tagihan (Rp)</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 12px;">
                <div>
                    <small style="color: var(--text-muted); display: block; margin-bottom: 4px;">Tagihan Air</small>
                    <div id="total_tagihan_air" style="font-size: 18px; font-weight: 600; color: #059669;">Rp 0</div>
                </div>
                <div>
                    <small style="color: var(--text-muted); display: block; margin-bottom: 4px;">Biaya Pemeliharaan</small>
                    <div id="total_biaya_pemeliharaan" style="font-size: 18px; font-weight: 600; color: #059669;">Rp 0</div>
                </div>
            </div>
            <div style="border-top: 2px solid #86efac; margin-top: 12px; padding-top: 12px;">
                <small style="color: var(--text-muted); display: block; margin-bottom: 4px;">Total Tagihan</small>
                <div id="total_tagihan_final" style="font-size: 28px; font-weight: 700; color: #059669;">Rp 0</div>
            </div>
            <small style="color: var(--text-muted); display: block; margin-top: 12px;">*Perhitungan otomatis berdasarkan pemakaian dan tarif progresif</small>
        </div>

        <div style="margin-top: 24px; text-align: right; display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('billings.index') }}" class="btn btn-secondary" style="background: var(--body-bg); color: var(--text-main);">Batal</a>
            <button type="submit" class="btn btn-primary">Hitung & Simpan Tagihan</button>
        </div>
    </form>
</div>

<script>
    function loadMeterAwal() {
        const pelangganId = document.getElementById('pelanggan_id').value;

        if (!pelangganId) {
            document.getElementById('meter_awal').value = 0;
            document.getElementById('no_invoice').value = '';
            resetCalculation();
            return;
        }

        // Fetch meter akhir dari billing terakhir pelanggan
        Promise.all([
                fetch(`/api/billings/last-meter/${pelangganId}`).then(r => r.json()),
                fetch(`/api/billings/generate-invoice`).then(r => r.json())
            ])
            .then(([meterData, invoiceData]) => {
                // Set meter awal dari meter_awal yang dikembalikan API
                document.getElementById('meter_awal').value = meterData.meter_awal || 0;
                // Set no invoice
                document.getElementById('no_invoice').value = invoiceData.no_invoice || '';
                calculatePemakaian();
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('meter_awal').value = 0;
                document.getElementById('no_invoice').value = '';
                resetCalculation();
            });
    }

    function calculatePemakaian() {
        let awal = parseInt(document.getElementById('meter_awal').value) || 0;
        let akhir = parseInt(document.getElementById('meter_akhir').value) || 0;
        let pemakaian = akhir - awal;
        if (pemakaian < 0) pemakaian = 0;
        document.getElementById('estimasi_pemakaian').innerText = pemakaian;

        // Calculate total billing
        calculateTotalBilling();
    }

    function calculateTotalBilling() {
        const pelangganId = document.getElementById('pelanggan_id').value;
        const meterAwal = parseInt(document.getElementById('meter_awal').value) || 0;
        const meterAkhir = parseInt(document.getElementById('meter_akhir').value) || 0;

        if (!pelangganId) {
            resetCalculation();
            return;
        }

        // Call API to calculate total billing
        fetch('/api/billings/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    pelanggan_id: pelangganId,
                    meter_awal: meterAwal,
                    meter_akhir: meterAkhir
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Format numbers as Indonesian Rupiah
                    const tagihanAir = formatRupiah(data.tagihan_air);
                    const biayaPemeliharaan = formatRupiah(data.biaya_pemeliharaan);
                    const totalTagihan = formatRupiah(data.total_tagihan);

                    document.getElementById('total_tagihan_air').innerText = tagihanAir;
                    document.getElementById('total_biaya_pemeliharaan').innerText = biayaPemeliharaan;
                    document.getElementById('total_tagihan_final').innerText = totalTagihan;
                } else {
                    resetCalculation();
                    console.error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resetCalculation();
            });
    }

    function resetCalculation() {
        document.getElementById('total_tagihan_air').innerText = 'Rp 0';
        document.getElementById('total_biaya_pemeliharaan').innerText = 'Rp 0';
        document.getElementById('total_tagihan_final').innerText = 'Rp 0';
    }

    function formatRupiah(value) {
        return 'Rp ' + parseFloat(value).toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    // Load meter awal jika pelanggan sudah dipilih (saat edit)
    document.addEventListener('DOMContentLoaded', function() {
        const pelangganSelect = document.getElementById('pelanggan_id');

        // Load data saat page load jika ada pelanggan yang dipilih
        if (pelangganSelect.value) {
            loadMeterAwal();
        }

        // Add event listener untuk perubahan pelanggan
        pelangganSelect.addEventListener('change', function() {
            loadMeterAwal();
        });

        calculatePemakaian();
    });
</script>
@endsection