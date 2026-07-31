@extends('layouts.cms')
@php $pageTitle = 'Detail Pesan'; $breadcrumb = 'CMS / Guest Book / Detail'; @endphp
@section('content')
<div style="display:grid;grid-template-columns:1fr 280px;gap:24px;align-items:start">
    {{-- Isi Pesan --}}
    <div class="card card-body">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px">
            <div style="width:48px;height:48px;border-radius:14px;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <p style="font-size:16px;font-weight:800;color:#0f172a">{{ $guestBook->nama }}</p>
                <p style="font-size:13px;color:#64748b">{{ $guestBook->email }}@if($guestBook->no_hp) &nbsp;·&nbsp; {{ $guestBook->no_hp }}@endif</p>
            </div>
            @php $bc=['baru'=>'badge-amber','dibaca'=>'badge-blue','selesai'=>'badge-green'][$guestBook->status]??'badge-slate'; @endphp
            <span class="badge {{ $bc }}" style="margin-left:auto;font-size:13px;padding:5px 12px">{{ ucfirst($guestBook->status) }}</span>
        </div>

        <div style="background:#f8fafc;border-radius:12px;padding:20px;margin-bottom:20px">
            <p style="font-size:11px;font-weight:700;color:#94a3b8;margin-bottom:8px;text-transform:uppercase;letter-spacing:.05em">PESAN</p>
            <p style="font-size:14px;color:#334155;line-height:1.8;white-space:pre-line">{{ $guestBook->pesan }}</p>
        </div>

        <p style="font-size:12px;color:#94a3b8"><i class="fas fa-clock" style="margin-right:4px"></i>Dikirim: {{ $guestBook->created_tm->format('d M Y, H:i') }} WIB</p>
    </div>

    {{-- Sidebar Aksi --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card card-body">
            <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:14px">Update Status</p>
            <form method="POST" action="{{ route('cms.guest-book.mark',$guestBook) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <select name="status" class="form-input" required>
                        <option value="baru"    {{ $guestBook->status==='baru'?'selected':'' }}>Baru</option>
                        <option value="dibaca"  {{ $guestBook->status==='dibaca'?'selected':'' }}>Sudah Dibaca</option>
                        <option value="selesai" {{ $guestBook->status==='selesai'?'selected':'' }}>Selesai</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center"><i class="fas fa-save"></i> Simpan Status</button>
            </form>
        </div>
        <form method="POST" action="{{ route('cms.guest-book.destroy',$guestBook) }}" onsubmit="return confirm('Hapus pesan ini secara permanen?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center"><i class="fas fa-trash"></i> Hapus Pesan</button>
        </form>
        <a href="{{ route('cms.guest-book') }}" class="btn btn-secondary" style="justify-content:center"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>
@endsection
