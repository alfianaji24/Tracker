@extends('layouts.app')

@section('content')
<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
    <h2 class="page-title" style="margin-bottom: 0;">Tambah Permission</h2>
</div>

<div style="background: white; border-radius: 8px; padding: 24px; max-width: 600px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

    <form method="POST" action="{{ route('master.permissions.store') }}">
        @csrf

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Nama Permission</label>
            <input type="text" name="name" value="{{ old('name') }}" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;" required>
            @error('name')
            <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Slug</label>
            <input type="text" name="slug" value="{{ old('slug') }}" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;" required>
            @error('slug')
            <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Deskripsi</label>
            <textarea name="description" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit; resize: vertical; min-height: 80px;">{{ old('description') }}</textarea>
            @error('description')
            <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" style="background: var(--primary); color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Simpan</button>
            <a href="{{ route('master.permissions.index') }}" style="background: #e5e7eb; color: #374151; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-block;">Batal</a>
        </div>
    </form>
</div>
</div>
@endsection
