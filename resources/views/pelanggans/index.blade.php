@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 class="page-title" style="margin-bottom: 0;">Data Pelanggan</h2>
    <a href="{{ route('pelanggans.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> Tambah Pelanggan
    </a>
</div>

<div class="card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>No Pelanggan</th>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Perumahan</th>
                    <th>Blok</th>
                    <th>Golongan Tarif</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggans as $p)
                <tr>
                    <td style="font-weight: 500;">{{ $p->no_pelanggan }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->no_telp ?? '-' }}</td>
                    <td>{{ $p->perumahan ?? '-' }}</td>
                    <td>{{ $p->blok ?? '-' }}</td>
                    <td>{{ $p->tarifAir->golongan ?? '-' }} ({{ $p->tarifAir->kode ?? '-' }})</td>
                    <td>
                        @if($p->status == 'aktif')
                        <span class="badge badge-success">Aktif</span>
                        @else
                        <span class="badge badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('pelanggans.edit', $p->id) }}" class="btn btn-primary btn-sm" style="background: #f1f5f9; color: var(--primary);">
                                <i class='bx bx-edit-alt'></i>
                            </a>
                            <form action="{{ route('pelanggans.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                    <td colspan="8" style="text-align: center; padding: 24px; color: var(--text-muted);">Belum ada data pelanggan terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection