@extends('layouts.app')

@section('content')
<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
    <h2 class="page-title" style="margin-bottom: 0;">Edit Data Pelanggan</h2>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ route('pelanggans.update', $pelanggan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">No Pelanggan</label>
                <input type="text" name="no_pelanggan" class="form-control" value="{{ old('no_pelanggan', $pelanggan->no_pelanggan) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="aktif" {{ old('status', $pelanggan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $pelanggan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $pelanggan->nama) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">No Handphone / WhatsApp</label>
            <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $pelanggan->no_telp) }}">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Perumahan (Misal: Kota Cakra)</label>
                <input type="text" name="perumahan" class="form-control" value="{{ old('perumahan', $pelanggan->perumahan) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Blok / No Rumah</label>
                <input type="text" name="blok" class="form-control" value="{{ old('blok', $pelanggan->blok) }}">
            </div>
        </div>

        <hr style="margin: 24px 0; border: none; border-top: 1px solid var(--border-color);">

        <div class="form-group">
            <label class="form-label">Golongan Tarif Air</label>
            <select name="tarif_air_id" class="form-control" required>
                <option value="">-- Pilih Golongan Tarif --</option>
                @foreach($tarifs as $t)
                <option value="{{ $t->id }}" {{ old('tarif_air_id', $pelanggan->tarif_air_id) == $t->id ? 'selected' : '' }}>
                    {{ $t->kode }} - {{ $t->golongan }} (Beban: Rp {{ number_format($t->biaya_pemeliharaan, 0, ',', '.') }})
                </option>
                @endforeach
            </select>
        </div>

        <div style="margin-top: 24px; text-align: right;">
            <button type="submit" class="btn btn-primary">Update Pelanggan</button>
        </div>
    </form>
</div>
@endsection