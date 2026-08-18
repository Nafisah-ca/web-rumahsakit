@extends('layouts.admin')
@php $pageTitle = 'Jadwal Dokter'; $breadcrumb = 'Admin / Jadwal'; @endphp
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Jadwal Praktik Dokter</h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <form style="display:flex;gap:8px" method="GET">
                <select name="dokter_id" class="form-input" style="width:200px">
                    <option value="">Semua Dokter</option>
                    @foreach($dokters as $d)
                    <option value="{{ $d->id }}" {{ request('dokter_id')==$d->id?'selected':'' }}>{{ $d->nama_dokter }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i></button>
                @if(request('dokter_id'))<a href="{{ route('admin.jadwal') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Jadwal</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Dokter</th><th>Spesialisasi</th><th>Hari</th><th>Tanggal Praktek</th><th>Jam Praktik</th><th>Kuota</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($jadwals as $i => $j)
                @php
                    $today      = now()->toDateString();
                    $nowTime    = now()->format('H:i:s');
                    $praktek    = $j->tanggal_praktek?->toDateString();
                    $kedaluarsa = $praktek && (
                        $praktek < $today ||
                        ($praktek === $today && $nowTime >= $j->jam_selesai)
                    );
                @endphp
                <tr style="{{ $kedaluarsa ? 'opacity:0.6' : '' }}">
                    <td style="color:#94a3b8">{{ $jadwals->firstItem()+$i }}</td>
                    <td style="font-weight:600;font-size:13px">{{ $j->dokter?->nama_dokter ?? '-' }}</td>
                    <td style="font-size:12px;color:#64748b">{{ $j->spesialisasi?->nama_spesialis ?? '-' }}</td>
                    <td><span class="badge badge-blue">{{ $j->hari }}</span></td>
                    <td style="font-size:12px;color:#64748b">
                        {{ $j->tanggal_praktek?->format('d M Y') ?? '-' }}
                        @if($kedaluarsa)
                            <span class="badge badge-slate" style="font-size:10px;margin-left:4px">Kedaluarsa</span>
                        @endif
                    </td>
                    <td><span class="code-tag">{{ substr($j->jam_mulai,0,5) }} – {{ substr($j->jam_selesai,0,5) }}</span></td>
                    <td style="font-weight:600">{{ $j->kuota }} <span style="font-size:11px;color:#94a3b8;font-weight:400">pasien</span></td>
                    <td>
                        @if($kedaluarsa)
                            <span class="badge badge-slate">Selesai</span>
                        @else
                            <span class="badge {{ $j->status==='aktif' ? 'badge-green' : 'badge-slate' }}">
                                {{ $j->status==='aktif' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('admin.jadwal.edit',$j) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="{{ route('admin.jadwal.destroy',$j) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9"><div class="empty-state"><i class="fas fa-clock"></i><p>Tidak ada jadwal</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $jadwals->links() }}</div>
</div>
@endsection
