@extends('layouts.admin')
@php $pageTitle = 'Jadwal Dokter'; $breadcrumb = 'Admin / Jadwal'; @endphp
@section('content')

{{-- Tab Navigasi --}}
<div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;align-items:center">
    <a href="{{ route('admin.jadwal', array_merge(request()->except('page'), ['tab' => 'mendatang'])) }}" 
       class="btn {{ ($tab ?? 'mendatang') === 'mendatang' ? 'btn-primary' : 'btn-secondary' }}"
       style="border-radius:10px;font-size:13px;display:inline-flex;align-items:center;gap:6px">
        <i class="fas fa-calendar-check"></i>
        <span>Jadwal Aktif & Mendatang</span>
        <span style="background:{{ ($tab ?? 'mendatang') === 'mendatang' ? 'rgba(255,255,255,0.25)' : '#e2e8f0' }};
                     color:{{ ($tab ?? 'mendatang') === 'mendatang' ? '#fff' : '#475569' }};
                     font-size:11px;font-weight:700;padding:1px 7px;border-radius:99px">
            {{ $totalMendatang ?? 0 }}
        </span>
    </a>

    <a href="{{ route('admin.jadwal', array_merge(request()->except('page'), ['tab' => 'riwayat'])) }}" 
       class="btn {{ ($tab ?? '') === 'riwayat' ? 'btn-primary' : 'btn-secondary' }}"
       style="border-radius:10px;font-size:13px;display:inline-flex;align-items:center;gap:6px">
        <i class="fas fa-history"></i>
        <span>Riwayat Lewat</span>
        <span style="background:{{ ($tab ?? '') === 'riwayat' ? 'rgba(255,255,255,0.25)' : '#e2e8f0' }};
                     color:{{ ($tab ?? '') === 'riwayat' ? '#fff' : '#475569' }};
                     font-size:11px;font-weight:700;padding:1px 7px;border-radius:99px">
            {{ $totalRiwayat ?? 0 }}
        </span>
    </a>

    <a href="{{ route('admin.jadwal', array_merge(request()->except('page'), ['tab' => 'semua'])) }}" 
       class="btn {{ ($tab ?? '') === 'semua' ? 'btn-primary' : 'btn-secondary' }}"
       style="border-radius:10px;font-size:13px;display:inline-flex;align-items:center;gap:6px">
        <i class="fas fa-list"></i>
        <span>Semua Jadwal</span>
        <span style="background:{{ ($tab ?? '') === 'semua' ? 'rgba(255,255,255,0.25)' : '#e2e8f0' }};
                     color:{{ ($tab ?? '') === 'semua' ? '#fff' : '#475569' }};
                     font-size:11px;font-weight:700;padding:1px 7px;border-radius:99px">
            {{ $totalSemua ?? 0 }}
        </span>
    </a>

    <div style="margin-left:auto">
        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 style="margin:0">
                @if(($tab ?? 'mendatang') === 'riwayat')
                    Riwayat Jadwal Praktik (Sudah Lewat)
                @elseif(($tab ?? '') === 'semua')
                    Semua Jadwal Praktik Dokter
                @else
                    Jadwal Praktik Dokter (Aktif & Hari Ini ke Depan)
                @endif
            </h3>
            <p style="font-size:12px;color:#64748b;margin:3px 0 0 0">
                @if(($tab ?? 'mendatang') === 'mendatang')
                    <i class="fas fa-info-circle text-green-600 mr-1"></i> Jadwal otomatis terupdate setiap hari — tanggal yang sudah lewat otomatis masuk ke tab Riwayat Lewat.
                @elseif(($tab ?? '') === 'riwayat')
                    <i class="fas fa-info-circle text-slate-500 mr-1"></i> Menampilkan jadwal praktik pada tanggal yang telah terlewati.
                @else
                    <i class="fas fa-info-circle text-blue-500 mr-1"></i> Menampilkan seluruh data jadwal di sistem.
                @endif
            </p>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <form style="display:flex;gap:8px;flex-wrap:wrap;align-items:center" method="GET">
                <input type="hidden" name="tab" value="{{ $tab ?? 'mendatang' }}">

                <select name="dokter_id" class="form-input" style="width:190px">
                    <option value="">Semua Dokter</option>
                    @foreach($dokters as $d)
                    <option value="{{ $d->id }}" {{ request('dokter_id')==$d->id?'selected':'' }}>{{ $d->nama_dokter }}</option>
                    @endforeach
                </select>

                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="form-input" style="width:140px" title="Tanggal Dari">
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="form-input" style="width:140px" title="Tanggal Sampai">

                <button type="submit" class="btn btn-secondary" title="Filter"><i class="fas fa-filter"></i></button>
                @if(request('dokter_id') || request('tanggal_dari') || request('tanggal_sampai'))
                <a href="{{ route('admin.jadwal', ['tab' => $tab ?? 'mendatang']) }}" class="btn btn-secondary" title="Reset Filter"><i class="fas fa-xmark"></i></a>
                @endif
            </form>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Dokter</th>
                    <th>Spesialisasi</th>
                    <th>Hari</th>
                    <th>Tanggal Praktek</th>
                    <th>Jam Praktik</th>
                    <th>Kuota</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $i => $j)
                @php
                    $today      = now()->toDateString();
                    $tomorrow   = now()->addDay()->toDateString();
                    $nowTime    = now()->format('H:i:s');
                    $praktek    = $j->tanggal_praktek?->toDateString();
                    $isToday    = $praktek === $today;
                    $isTomorrow = $praktek === $tomorrow;
                    $kedaluarsa = $praktek && (
                        $praktek < $today ||
                        ($praktek === $today && $nowTime >= $j->jam_selesai)
                    );
                @endphp
                <tr style="{{ $kedaluarsa ? 'background:#fafafa;opacity:0.75' : ($isToday ? 'background:#f0fdf4' : '') }}">
                    <td style="color:#94a3b8">{{ $jadwals->firstItem()+$i }}</td>
                    <td>
                        <div style="font-weight:700;font-size:13px;color:#0f172a">{{ $j->dokter?->nama_dokter ?? '-' }}</div>
                        <div style="font-size:11px;color:#94a3b8">SIP: {{ $j->dokter?->sip ?? '-' }}</div>
                    </td>
                    <td style="font-size:12px;color:#64748b">
                        <span class="badge badge-slate" style="font-size:11px">{{ $j->spesialisasi?->nama_spesialis ?? '-' }}</span>
                    </td>
                    <td><span class="badge badge-blue">{{ $j->hari }}</span></td>
                    <td>
                        <div style="font-size:13px;font-weight:600;color:#334155">
                            {{ $j->tanggal_praktek?->format('d M Y') ?? '-' }}
                        </div>
                        @if($isToday)
                            <span class="badge badge-green" style="font-size:10px;margin-top:2px">Hari Ini</span>
                        @elseif($isTomorrow)
                            <span class="badge badge-blue" style="font-size:10px;margin-top:2px">Besok</span>
                        @elseif($kedaluarsa)
                            <span class="badge badge-slate" style="font-size:10px;margin-top:2px">Sudah Lewat</span>
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
                <tr>
                    <td colspan="9">
                        <div class="empty-state" style="padding:40px 0;text-align:center">
                            <i class="fas fa-calendar-times" style="font-size:36px;color:#cbd5e1;margin-bottom:12px;display:block"></i>
                            <p style="font-weight:700;color:#64748b;font-size:14px;margin-bottom:4px">
                                @if(($tab ?? 'mendatang') === 'riwayat')
                                    Tidak ada riwayat jadwal yang sudah lewat
                                @else
                                    Tidak ada jadwal aktif/mendatang yang ditemukan
                                @endif
                            </p>
                            <p style="font-size:12px;color:#94a3b8;margin-bottom:16px">
                                Tambahkan jadwal praktik baru untuk dokter di rumah sakit.
                            </p>
                            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Tambah Jadwal Baru
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $jadwals->links() }}</div>
</div>
@endsection
