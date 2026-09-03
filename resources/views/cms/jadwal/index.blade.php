@extends('layouts.cms')
@php $pageTitle = 'Jadwal Dokter'; $breadcrumb = 'CMS / Jadwal Dokter'; @endphp
@section('content')

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-clock" style="color:#2563eb;margin-right:6px"></i>Jadwal Praktik Dokter</h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
            <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap">
                <select name="dokter_id" class="form-input" style="width:200px">
                    <option value="">Semua Dokter</option>
                    @foreach($dokterList as $d)
                    <option value="{{ $d->id }}" {{ request('dokter_id')==$d->id?'selected':'' }}>{{ $d->nama_dokter }}</option>
                    @endforeach
                </select>
                <select name="hari" class="form-input" style="width:130px">
                    <option value="">Semua Hari</option>
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
                    <option value="{{ $h }}" {{ request('hari')===$h?'selected':'' }}>{{ $h }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-filter"></i></button>
                @if(request()->hasAny(['dokter_id','hari']))
                <a href="{{ route('cms.jadwal') }}" class="btn btn-secondary btn-sm"><i class="fas fa-xmark"></i></a>
                @endif
            </form>
            <a href="{{ route('cms.jadwal.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Jadwal
            </a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Dokter</th>
                    <th>Spesialisasi / Poli</th>
                    <th>Hari</th>
                    <th>Jam Praktik</th>
                    <th>Kuota</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $i => $j)
                <tr>
                    <td class="text-muted">{{ $jadwals->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            @if($j->dokter?->foto)
                            <img src="{{ Storage::url($j->dokter->foto) }}"
                                 style="width:32px;height:32px;border-radius:8px;object-fit:cover;flex-shrink:0;object-position:top">
                            @else
                            <div style="width:32px;height:32px;border-radius:8px;background:#e0e7ff;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="fas fa-user-doctor" style="color:#4338ca;font-size:12px"></i>
                            </div>
                            @endif
                            <span style="font-weight:600;font-size:13px">{{ $j->dokter?->nama_dokter ?? '-' }}</span>
                        </div>
                    </td>
                    <td><span class="badge badge-blue">{{ $j->spesialisasi?->nama_spesialis ?? '-' }}</span></td>
                    <td><span class="badge badge-purple">{{ $j->hari }}</span></td>
                    <td>
                        <span class="code-tag">{{ substr($j->jam_mulai,0,5) }} – {{ substr($j->jam_selesai,0,5) }}</span>
                    </td>
                    <td style="font-weight:700;text-align:center">
                        {{ $j->kuota }}
                        <span style="font-size:11px;color:#94a3b8;font-weight:400">pasien</span>
                    </td>
                    <td>
                        <span class="badge {{ $j->status==='aktif' ? 'badge-green' : 'badge-slate' }}">
                            {{ $j->status==='aktif' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('cms.jadwal.edit', $j) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('cms.jadwal.destroy', $j) }}"
                                  onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-clock"></i>
                            <p>Belum ada jadwal. <a href="{{ route('cms.jadwal.create') }}" style="color:#2563eb">Tambah sekarang</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($jadwals->hasPages())
    <div class="table-footer">{{ $jadwals->links() }}</div>
    @endif
</div>
@endsection
