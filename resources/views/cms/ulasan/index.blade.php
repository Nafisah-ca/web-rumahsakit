@extends('layouts.cms')
@php $pageTitle = 'Ulasan Pasien'; $breadcrumb = 'CMS / Ulasan Pasien'; @endphp

@section('content')

{{-- Stats bar --}}
<div class="cms-stats-5 stats-grid" style="margin-bottom:24px">
    @foreach([
        ['Total',      $stats['total'],    'fa-star',        '#6366f1','#eef2ff'],
        ['Menunggu',   $stats['pending'],  'fa-clock',       '#f59e0b','#fffbeb'],
        ['Ditampilkan',$stats['approved'], 'fa-check-circle','#16a34a','#f0fdf4'],
        ['Ditolak',    $stats['rejected'], 'fa-times-circle','#dc2626','#fef2f2'],
        ['Rata-rata',  $stats['avg'].'★',  'fa-star-half-alt','#f59e0b','#fffbeb'],
    ] as [$lbl,$val,$ico,$color,$bg])
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:14px;display:flex;align-items:center;gap:10px">
        <div style="width:36px;height:36px;border-radius:10px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="fas {{ $ico }}" style="color:{{ $color }};font-size:14px"></i>
        </div>
        <div style="min-width:0">
            <p style="font-size:18px;font-weight:800;color:#0f172a;line-height:1">{{ $val }}</p>
            <p style="font-size:11px;color:#64748b;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $lbl }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <h3>Daftar Ulasan</h3>
        <form style="display:flex;gap:8px;flex-wrap:wrap" method="GET">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / isi..." class="form-input" style="width:200px">
            <select name="status" class="form-input" style="width:140px" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending"  {{ request('status')==='pending'  ? 'selected' : '' }}>Menunggu</option>
                <option value="approved" {{ request('status')==='approved' ? 'selected' : '' }}>Ditampilkan</option>
                <option value="rejected" {{ request('status')==='rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="rating" class="form-input" style="width:130px" onchange="this.form.submit()">
                <option value="">Semua Rating</option>
                @foreach([5,4,3,2,1] as $r)
                <option value="{{ $r }}" {{ request('rating')==$r ? 'selected' : '' }}>{{ $r }} Bintang</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
            @if(request('search') || request('status') || request('rating'))
            <a href="{{ route('cms.ulasan') }}" class="btn btn-secondary" title="Reset"><i class="fas fa-xmark"></i></a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Rating</th>
                    <th>Ulasan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ulasans as $i => $u)
                <tr>
                    <td style="color:#94a3b8">{{ $ulasans->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:32px;height:32px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <span style="color:#166534;font-weight:800;font-size:13px">{{ strtoupper(substr($u->nama,0,1)) }}</span>
                            </div>
                            <div>
                                <p style="font-weight:600;font-size:13px;color:#0f172a">{{ $u->nama }}</p>
                                @if($u->email)
                                <p style="font-size:11px;color:#94a3b8">{{ $u->email }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;gap:1px">
                            @for($s = 1; $s <= 5; $s++)
                            <i class="fas fa-star" style="font-size:12px;color:{{ $s <= $u->rating ? '#f59e0b' : '#e2e8f0' }}"></i>
                            @endfor
                        </div>
                        <span style="font-size:11px;color:#64748b">{{ $u->rating }}/5</span>
                    </td>
                    <td style="max-width:300px">
                        @if($u->judul)
                        <p style="font-weight:600;font-size:12px;color:#0f172a;margin-bottom:2px">{{ $u->judul }}</p>
                        @endif
                        <p style="font-size:12px;color:#64748b;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">{{ $u->isi }}</p>
                    </td>
                    <td>
                        @php
                            $statusStyle = match($u->status) {
                                'approved' => 'background:#f0fdf4;color:#16a34a;border:1px solid #86efac',
                                'rejected' => 'background:#fef2f2;color:#dc2626;border:1px solid #fca5a5',
                                default    => 'background:#fffbeb;color:#d97706;border:1px solid #fcd34d',
                            };
                        @endphp
                        <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;{{ $statusStyle }}">
                            {{ $u->status_label }}
                        </span>
                    </td>
                    <td style="color:#64748b;font-size:12px">
                        {{ $u->created_tm?->format('d M Y') }}
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            {{-- Approve --}}
                            @if($u->status !== 'approved')
                            <form method="POST" action="{{ route('cms.ulasan.mark', $u) }}"
                                  onsubmit="return confirm('Tampilkan ulasan ini di portal?')">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button class="btn btn-sm btn-primary" title="Tampilkan">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif

                            {{-- Reject --}}
                            @if($u->status !== 'rejected')
                            <form method="POST" action="{{ route('cms.ulasan.mark', $u) }}"
                                  onsubmit="return confirm('Tolak ulasan ini?')">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button class="btn btn-sm btn-secondary" title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @endif

                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('cms.ulasan.destroy', $u) }}"
                                  onsubmit="return confirm('Hapus ulasan ini permanen?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-star"></i>
                            <p>Belum ada ulasan masuk</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $ulasans->links() }}</div>
</div>
@endsection
