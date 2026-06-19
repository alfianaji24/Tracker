@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 class="page-title" style="margin-bottom: 0;">Master Tarif Air</h2>
    <a href="{{ route('master.tarif-air.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> Tambah Tarif
    </a>
</div>

<div class="card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Golongan</th>
                    <th>Blok 1 (0-10)</th>
                    <th>Blok 2 (11-20)</th>
                    <th>Blok 3 (21-30)</th>
                    <th>Blok 4 (>30)</th>
                    <th>Pemeliharaan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tarifs as $t)
                <tr>
                    <td style="font-weight: 500;">{{ $t->kode }}</td>
                    <td>{{ $t->golongan }}</td>
                    <td>Rp {{ number_format($t->tarif_blok_1, 0, ',', '.') }}/m³</td>
                    <td>Rp {{ number_format($t->tarif_blok_2, 0, ',', '.') }}/m³</td>
                    <td>Rp {{ number_format($t->tarif_blok_3, 0, ',', '.') }}/m³</td>
                    <td>Rp {{ number_format($t->tarif_blok_4, 0, ',', '.') }}/m³</td>
                    <td>Rp {{ number_format($t->biaya_pemeliharaan, 0, ',', '.') }}</td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('master.tarif-air.edit', $t->id) }}" class="btn btn-primary btn-sm" style="background: #f1f5f9; color: var(--primary);">
                                <i class='bx bx-edit-alt'></i>
                            </a>
                            <form action="{{ route('master.tarif-air.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                    <td colspan="8" style="text-align: center; padding: 24px; color: var(--text-muted);">Belum ada data tarif air.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection