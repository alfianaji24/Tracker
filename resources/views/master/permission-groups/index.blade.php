@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 24px; font-weight: 700;">Permission Groups</h2>
        <a href="{{ route('master.permission-groups.create') }}" style="background: var(--primary); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
            + Tambah Group
        </a>
    </div>

    @if(session('success'))
    <div style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #22c55e;">
        {{ session('success') }}
    </div>
    @endif

    <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f3f4f6; border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151;">ID</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151;">Nama Group</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151;">Permissions</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151;">Deskripsi</th>
                    <th style="padding: 12px 16px; text-align: center; font-weight: 600; color: #374151;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groups as $group)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px 16px; color: #6b7280;">{{ $group->id }}</td>
                    <td style="padding: 12px 16px; color: #374151; font-weight: 500;">{{ $group->name }}</td>
                    <td style="padding: 12px 16px;">
                        <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 13px;">
                            {{ $group->permissions_count }} permission
                        </span>
                    </td>
                    <td style="padding: 12px 16px; color: #6b7280;">{{ Str::limit($group->description, 50) }}</td>
                    <td style="padding: 12px 16px; text-align: center;">
                        <a href="{{ route('master.permission-groups.edit', $group) }}" style="color: #3b82f6; text-decoration: none; font-size: 14px; margin-right: 12px;">Edit</a>
                        <form method="POST" action="{{ route('master.permission-groups.destroy', $group) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus group ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer; font-size: 14px;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 24px; text-align: center; color: #9ca3af;">Tidak ada data permission group.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
