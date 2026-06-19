@extends('layouts.app')

@section('content')
<div style="padding: 24px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('master.users.index') }}" style="color: #3b82f6; text-decoration: none;">← Kembali</a>
    </div>

    <div style="background: white; border-radius: 8px; padding: 24px; max-width: 600px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 24px;">Edit User</h2>

        <form method="POST" action="{{ route('master.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;" required>
                @error('name')
                <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;" required>
                @error('email')
                <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Password Baru (kosongkan jika tidak ingin diubah)</label>
                <input type="password" name="password" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                @error('password')
                <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Role</label>
                <select name="role_id" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                <span style="color: #ef4444; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} style="margin-right: 8px;">
                    <span style="color: #374151;">Aktifkan User</span>
                </label>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" style="background: var(--primary); color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Simpan</button>
                <a href="{{ route('master.users.index') }}" style="background: #e5e7eb; color: #374151; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-block;">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection