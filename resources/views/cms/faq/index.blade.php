@extends('layouts.cms')
@php $pageTitle = 'FAQ'; $breadcrumb = 'CMS / FAQ'; @endphp
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar FAQ</h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <form style="display:flex;gap:8px;flex-wrap:wrap" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pertanyaan..." class="form-input" style="min-width:140px;flex:1">
                <select name="status" class="form-input" style="min-width:110px;flex:1">
                    <option value="">Semua Status</option>
                    <option value="aktif"    {{ request('status')==='aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status')==='nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request('search') || request('status'))
                    <a href="{{ route('cms.faq') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>
                @endif
            </form>
            <a href="{{ route('cms.faq.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah FAQ</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:50px">No.</th>
                    <th>Pertanyaan</th>
                    <th style="width:80px">Urutan</th>
                    <th style="width:100px">Status</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $f)
                <tr>
                    <td style="color:#94a3b8;font-size:12px">{{ $faqs->firstItem() + $loop->index }}</td>
                    <td>
                        <p style="font-weight:600;font-size:13px;color:#0f172a;margin-bottom:4px">{{ $f->pertanyaan }}</p>
                        <p style="font-size:11px;color:#94a3b8;overflow:hidden;text-overflow:ellipsis;white-space:normal;max-width:100%">
                            {{ Str::limit(strip_tags($f->jawaban), 80) }}
                        </p>
                    </td>
                    <td>
                        <span class="badge badge-slate">{{ $f->urutan }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $f->status === 'aktif' ? 'badge-green' : 'badge-slate' }}">
                            {{ $f->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('cms.faq.edit', $f) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('cms.faq.destroy', $f) }}"
                                  onsubmit="return confirm('Hapus FAQ ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-circle-question"></i>
                            <p>Belum ada FAQ. <a href="{{ route('cms.faq.create') }}" style="color:#2563eb">Tambah sekarang</a>.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $faqs->links() }}</div>
</div>
@endsection
