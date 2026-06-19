@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 12px;">
    <h2 class="page-title" style="margin-bottom: 0;">Tagihan Air (Billing)</h2>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('billings.swacam') }}" class="btn btn-secondary" style="background: #f0fdf4; color: #059669; border: 1px solid #86efac;">
            <i class='bx bx-camera'></i> swaCam
        </a>
        <a href="{{ route('billings.create') }}" class="btn btn-primary">
            <i class='bx bx-bolt-circle'></i> Buat Tagihan Manual
        </a>
    </div>
</div>

<div class="card">
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($billings as $b)
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
                    <td>
                        {{ $b->pemakaian }} m³<br>
                        <small style="color: var(--text-muted);">({{ $b->meter_awal }} - {{ $b->meter_akhir }})</small>
                    </td>
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
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('billings.edit', $b->id) }}" class="btn btn-primary btn-sm" style="background: #f1f5f9; color: var(--primary);">
                                <i class='bx bx-edit-alt'></i>
                            </a>
                            <form action="{{ route('billings.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tagihan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="background: #fee2e2; color: var(--danger);">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px; color: var(--text-muted);">Belum ada data tagihan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
