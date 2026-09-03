@extends('layouts.admin')
@php $pageTitle = 'Jadwal Dokter'; $breadcrumb = 'Admin / Jadwal'; @endphp
@section('content')

{{-- Header aksi --}}
<div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;align-items:center">
    <form style="display:flex;gap:8px;align-items:center;flex-wrap:wrap" method="GET">
        <select name="dokter_id" class="form-input" style="width:200px" onchange="this.form.submit()">
            <option value="">Semua Dokter</option>
            @foreach($dokters as $d)
            <option value="{{ $d->id }}" {{ request('dokter_id')==$d->id?'selected':'' }}>{{ $d->nama_dokter }}</option>
            @endforeach
        </select>
        @if(request('dokter_id'))
        <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary btn-sm"><i class="fas fa-xmark"></i></a>
        @endif
    </form>
    <div style="margin-left:auto;display:flex;gap:8px">
        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </a>
    </div>
</div>

{{-- Info --}}
<div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:12px;color:#1d4ed8;display:flex;align-items:flex-start;gap:8px">
    <i class="fas fa-circle-info" style="margin-top:1px;flex-shrink:0"></i>
    <span>
        <strong>Jadwal Mingguan Berulang</strong> (tanggal_praktek kosong) berlaku setiap minggu otomatis — tidak perlu diupdate.
        <strong>Kuota</strong> berlaku per tanggal, bukan total. Pasien bisa booking jauh ke depan selama kuota belum habis.
    </span>
</div>

@php
    /* Group jadwal by dokter */
    $byDokter = $jadwals->getCollection()->groupBy(fn($j) => $j->dokter?->nama_dokter ?? 'Tidak Diketahui');
    $hariUrut = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
@endphp

@if($byDokter->isEmpty())
<div class="card" style="text-align:center;padding:60px 24px">
    <i class="fas fa-calendar-times" style="font-size:40px;color:#cbd5e1;display:block;margin-bottom:12px"></i>
    <p style="font-weight:700;color:#64748b;font-size:14px;margin-bottom:4px">Belum ada jadwal</p>
    <p style="font-size:12px;color:#94a3b8;margin-bottom:16px">Tambahkan jadwal praktik dokter terlebih dahulu.</p>
    <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Jadwal</a>
</div>
@else

@foreach($byDokter as $namaDokter => $jadwalGroup)
@php
    $dokter = $jadwalGroup->first()->dokter;
    $sorted = $jadwalGroup->sortBy(fn($j) => array_search($j->hari, $hariUrut));
@endphp
<div class="card" style="margin-bottom:20px;overflow:hidden">
    {{-- Header dokter --}}
    <div style="padding:16px 20px;background:linear-gradient(135deg,#00521f,#00b04f);display:flex;align-items:center;gap:14px">
        {{-- Foto dokter --}}
        <div style="width:52px;height:52px;border-radius:50%;overflow:hidden;flex-shrink:0;border:2.5px solid rgba(255,255,255,.4);background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center">
            @if($dokter?->foto)
            @php $fotoUrl = str_starts_with($dokter->foto,'images/') ? asset($dokter->foto) : Storage::url($dokter->foto); @endphp
            <img src="{{ $fotoUrl }}" alt="" style="width:100%;height:100%;object-fit:cover">
            @else
            <i class="fas fa-user-md" style="color:rgba(255,255,255,.8);font-size:20px"></i>
            @endif
        </div>
        <div style="flex:1;min-width:0">
            <p style="font-size:15px;font-weight:800;color:#fff;margin:0">{{ $namaDokter }}</p>
            <p style="font-size:12px;color:rgba(255,255,255,.75);margin:3px 0 0">
                {{ $dokter?->spesialisasi?->nama_spesialis ?? '-' }}
                @if($dokter?->sip) &nbsp;·&nbsp; SIP: {{ $dokter->sip }} @endif
            </p>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <span style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:999px">
                {{ $sorted->count() }} Jadwal
            </span>
            <a href="{{ route('admin.jadwal.create', ['dokter_id'=>$dokter?->id]) }}"
               style="background:#fff;color:#00521f;font-size:11px;font-weight:700;padding:6px 12px;border-radius:8px;text-decoration:none;display:inline-flex;align-items:center;gap:5px;transition:opacity .15s"
               onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                <i class="fas fa-plus" style="font-size:10px"></i> Tambah
            </a>
        </div>
    </div>

    {{-- Tabel jadwal --}}
    <div style="overflow:hidden">
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="background:#f8fafc;border-bottom:1px solid #e5e7eb">
                    <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;width:120px">Hari</th>
                    <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Jam Praktek</th>
                    <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Tanggal</th>
                    <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Kuota</th>
                    <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Status</th>
                    <th style="padding:10px 16px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sorted as $idx => $j)
                @php
                    $isRecurring = !$j->tanggal_praktek;
                    $praktek     = $j->tanggal_praktek?->toDateString();
                    $today       = now()->toDateString();
                    $isToday     = $praktek === $today;
                    $isTomorrow  = $praktek === now()->addDay()->toDateString();
                    $kedaluarsa  = $praktek && ($praktek < $today || ($praktek === $today && now()->format('H:i:s') >= $j->jam_selesai));
                    $rowBg       = $idx%2===0 ? '#fff' : '#fafafa';
                @endphp
                <tr style="background:{{ $isToday?'#f0fdf4':($kedaluarsa?'#f8fafc':$rowBg) }};border-top:1px solid #f1f5f9;transition:background .1s"
                    onmouseover="if(!{{ $kedaluarsa?'true':'false' }})this.style.background='#f0fdf4'"
                    onmouseout="this.style.background='{{ $isToday?'#f0fdf4':($kedaluarsa?'#f8fafc':$rowBg) }}'">

                    <td style="padding:12px 16px">
                        <span style="display:inline-flex;align-items:center;gap:6px;background:{{ $kedaluarsa?'#f1f5f9':'#dcfce7' }};color:{{ $kedaluarsa?'#94a3b8':'#166534' }};font-size:12px;font-weight:700;padding:3px 12px;border-radius:999px">
                            {{ $j->hari }}
                        </span>
                    </td>

                    <td style="padding:12px 16px">
                        <span style="font-family:'Courier New',monospace;font-size:13px;font-weight:700;color:{{ $kedaluarsa?'#94a3b8':'#0f172a' }};background:{{ $kedaluarsa?'#f8fafc':'#f1f5f9' }};padding:3px 10px;border-radius:7px">
                            {{ substr($j->jam_mulai,0,5) }} – {{ substr($j->jam_selesai,0,5) }}
                        </span>
                    </td>

                    <td style="padding:12px 16px">
                        @if($isRecurring)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;color:#7c3aed;background:#f3e8ff;padding:3px 10px;border-radius:999px">
                                <i class="fas fa-rotate" style="font-size:9px"></i> Mingguan
                            </span>
                        @else
                            <span style="font-size:12px;font-weight:600;color:{{ $kedaluarsa?'#94a3b8':'#374151' }}">
                                {{ $j->tanggal_praktek?->format('d M Y') }}
                            </span>
                            @if($isToday)
                                <span style="font-size:10px;font-weight:700;color:#16a34a;background:#dcfce7;padding:1px 7px;border-radius:999px;margin-left:4px">Hari Ini</span>
                            @elseif($isTomorrow)
                                <span style="font-size:10px;font-weight:700;color:#1d4ed8;background:#dbeafe;padding:1px 7px;border-radius:999px;margin-left:4px">Besok</span>
                            @elseif($kedaluarsa)
                                <span style="font-size:10px;font-weight:700;color:#94a3b8;background:#f1f5f9;padding:1px 7px;border-radius:999px;margin-left:4px">Lewat</span>
                            @endif
                        @endif
                    </td>

                    <td style="padding:12px 16px">
                        <span style="font-size:13px;font-weight:700;color:{{ $kedaluarsa?'#94a3b8':'#0f172a' }}">
                            {{ $j->kuota }}
                            <span style="font-size:11px;color:#9ca3af;font-weight:400">pasien</span>
                        </span>
                    </td>

                    <td style="padding:12px 16px">
                        @if($kedaluarsa)
                        <span style="font-size:11px;font-weight:700;color:#94a3b8;background:#f1f5f9;padding:3px 10px;border-radius:999px">Selesai</span>
                        @else
                        <span style="font-size:11px;font-weight:700;color:{{ $j->status==='aktif'?'#166534':'#64748b' }};background:{{ $j->status==='aktif'?'#dcfce7':'#f1f5f9' }};padding:3px 10px;border-radius:999px">
                            {{ $j->status==='aktif'?'Aktif':'Nonaktif' }}
                        </span>
                        @endif
                    </td>

                    <td style="padding:12px 16px;text-align:center">
                        <div style="display:flex;gap:6px;justify-content:center">
                            <a href="{{ route('admin.jadwal.edit',$j) }}"
                               style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:8px;font-size:11px;font-weight:700;background:#f1f5f9;color:#475569;text-decoration:none;transition:background .15s"
                               onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                <i class="fas fa-pen" style="font-size:10px"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.jadwal.destroy',$j) }}" onsubmit="return confirm('Hapus jadwal ini?')" style="margin:0">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:8px;font-size:11px;font-weight:700;background:#fef2f2;color:#dc2626;border:none;cursor:pointer;transition:background .15s;font-family:inherit"
                                    onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                                    <i class="fas fa-trash" style="font-size:10px"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach

<div style="margin-top:8px">{{ $jadwals->links() }}</div>
@endif

@endsection
