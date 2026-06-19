@extends('layouts.app')

@section('content')
<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
    <h2 class="page-title" style="margin-bottom: 0;">Tambah Tarif Air</h2>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ route('master.tarif-air.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Kode Tarif</label>
                <input type="text" name="kode" class="form-control" value="{{ old('kode') }}" required placeholder="Cth: RT, BS">
                @error('kode')
                <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Golongan</label>
                <input type="text" name="golongan" class="form-control" value="{{ old('golongan') }}" required placeholder="Cth: Rumah Tangga">
                @error('golongan')
                <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="2" placeholder="Deskripsi tarif air (opsional)">{{ old('deskripsi') }}</textarea>
        </div>

        <hr style="margin: 24px 0; border: none; border-top: 1px solid var(--border-color);">
        <h4 style="margin-bottom: 16px; color: var(--text-main);">Tarif Progresif (Hardcoded Blok)</h4>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
            <strong>Catatan:</strong> Batasan blok sudah ditentukan sistem:<br>
            • Blok 1: 0 - 10 m³ | • Blok 2: 11 - 20 m³ | • Blok 3: 21 - 30 m³ | • Blok 4: > 30 m³
        </p>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Tarif Blok 1 (0-10 m³)</label>
                <div style="display: flex; gap: 8px;">
                    <span style="display: flex; align-items: center; color: var(--text-muted);">Rp</span>
                    <input type="number" name="tarif_blok_1" class="form-control" value="{{ old('tarif_blok_1') }}" required step="0.01">
                </div>
                @error('tarif_blok_1')
                <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tarif Blok 2 (11-20 m³)</label>
                <div style="display: flex; gap: 8px;">
                    <span style="display: flex; align-items: center; color: var(--text-muted);">Rp</span>
                    <input type="number" name="tarif_blok_2" class="form-control" value="{{ old('tarif_blok_2') }}" required step="0.01">
                </div>
                @error('tarif_blok_2')
                <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tarif Blok 3 (21-30 m³)</label>
                <div style="display: flex; gap: 8px;">
                    <span style="display: flex; align-items: center; color: var(--text-muted);">Rp</span>
                    <input type="number" name="tarif_blok_3" class="form-control" value="{{ old('tarif_blok_3') }}" required step="0.01">
                </div>
                @error('tarif_blok_3')
                <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tarif Blok 4 (> 30 m³)</label>
                <div style="display: flex; gap: 8px;">
                    <span style="display: flex; align-items: center; color: var(--text-muted);">Rp</span>
                    <input type="number" name="tarif_blok_4" class="form-control" value="{{ old('tarif_blok_4') }}" required step="0.01">
                </div>
                @error('tarif_blok_4')
                <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <hr style="margin: 24px 0; border: none; border-top: 1px solid var(--border-color);">
        <h4 style="margin-bottom: 16px; color: var(--text-main);">Biaya & Pengaturan Lainnya</h4>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Biaya Pemeliharaan / Bulan</label>
                <div style="display: flex; gap: 8px;">
                    <span style="display: flex; align-items: center; color: var(--text-muted);">Rp</span>
                    <input type="number" name="biaya_pemeliharaan" class="form-control" value="{{ old('biaya_pemeliharaan', 0) }}" required step="0.01">
                </div>
                @error('biaya_pemeliharaan')
                <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Minimal Pemakaian / Abonemen (m³)</label>
                <input type="number" name="minimal_pakai_m3" class="form-control" value="{{ old('minimal_pakai_m3', 5) }}" required min="1">
                @error('minimal_pakai_m3')
                <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div style="margin-top: 24px; text-align: right; display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('master.tarif-air.index') }}" class="btn btn-secondary" style="background: var(--body-bg); color: var(--text-main);">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Tarif</button>
        </div>
    </form>
</div>
@endsection
