@extends('layouts.admin')
@php $pageTitle = 'Pendaftaran MCU'; $breadcrumb = 'Admin / MCU'; @endphp
@section('content')

{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
    @foreach([
        ['label'=>'Total Pendaftaran', 'value'=>$totalAll,       'color'=>'#dbeafe','icon-color'=>'#1d4ed8', 'icon'=>'fa-clipboard-list'],
        ['label'=>'Menunggu',          'value'=>$totalMenunggu,  'color'=>'#fef3c7','icon-color'=>'#92400e', 'icon'=>'fa-clock'],
        ['label'=>'Dikonfirmasi',      'value'=>$totalKonfirm,   'color'=>'#dcfce7','icon-color'=>'#166534', 'icon'=>'fa-circle-check'],
        ['label'=>'Selesai',           'value'=>$totalSelesai,   'color'=>'#ede9fe','icon-color'=>'#6d28d9', 'icon'=>'fa-flag-checkered'],
    ] as $s)
    <div class="stat-card">
        <div class="stat-icon" style="background:{{ $s['color'] }};color:{{ $s['icon-color'] }}"><i class="fas {{ $s['icon'] }}"></i></div>
        <div class="stat-value">{{ $s['value'] }}</div>
        <div class="stat-label">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-clipboard-list" style="color:#16a34a;margin-right:6px"></i>Data Pendaftaran MCU</h3>
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap">
            <select name="paket" class="form-input" style="width:140px">
                <option value="">Semua Paket</option>
                @foreach(['basic'=>'Basic','standard'=>'Standard','executive'=>'Executive','corporate'=>'Corporate'] as $k=>$v)
                <option value="{{ $k }}" {{ request('paket')==$k?'selected':'' }}>{{ $v }}</option>
                @endforeach
            </select>
            <select name="status" class="form-input" style="width:150px">
                <option value="">Semua Status</option>
                @foreach(['menunggu'=>'Menunggu','dikonfirmasi'=>'Dikonfirmasi','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'] as $k=>$v)
                <option value="{{ $k }}" {{ request('status')==$k?'selected':'' }}>{{ $v }}</option>
                @endforeach
            </select>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kode..." class="form-input" style="width:200px">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
            @if(request()->hasAny(['paket','status','search']))
            <a href="{{ route('admin.mcu') }}" class="btn btn-secondary btn-sm"><i class="fas fa-xmark"></i> Reset</a>
            @endif
        </form>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>No. HP</th>
                    <th>Paket</th>
                    <th>Tanggal Pilihan</th>
                    <th>Sesi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftarans as $p)
                @php
                    $statusColor = match($p->status) {
                        'menunggu'     => 'badge-amber',
                        'dikonfirmasi' => 'badge-blue',
                        'selesai'      => 'badge-green',
                        'dibatalkan'   => 'badge-red',
                        default        => 'badge-slate',
                    };
                    $paketColor = match($p->paket) {
                        'basic'     => 'badge-green',
                        'standard'  => 'badge-blue',
                        'executive' => 'badge-purple',
                        'corporate' => 'badge-amber',
                        default     => 'badge-slate',
                    };
                @endphp
                <tr>
                    <td><span class="code-tag">{{ $p->kode_pendaftaran }}</span></td>
                    <td>
                        <p style="font-weight:600;color:#0f172a;font-size:13px">{{ $p->nama_lengkap }}</p>
                        @if($p->email)<p style="font-size:11px;color:#94a3b8">{{ $p->email }}</p>@endif
                    </td>
                    <td style="font-size:12px">{{ $p->no_hp }}</td>
                    <td><span class="badge {{ $paketColor }}">{{ $p->paket_label }}</span></td>
                    <td style="font-size:12px;white-space:nowrap">
                        {{ $p->tanggal_pilihan?->format('d/m/Y') }}
                    </td>
                    <td><span class="badge badge-slate">{{ ucfirst($p->sesi) }}</span></td>
                    <td>
                        <select onchange="updateStatus({{ $p->id }}, this.value)"
                                class="form-input" style="width:130px;padding:4px 8px;font-size:12px">
                            @foreach(['menunggu'=>'Menunggu','dikonfirmasi'=>'Dikonfirmasi','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'] as $k=>$v)
                            <option value="{{ $k }}" {{ $p->status==$k?'selected':'' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button onclick="showDetail({{ $p->id }})"
                                class="btn btn-secondary btn-sm btn-icon" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><i class="fas fa-clipboard-list"></i><p>Belum ada pendaftaran MCU</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pendaftarans->hasPages())
    <div class="table-footer">{{ $pendaftarans->links() }}</div>
    @endif
</div>

{{-- Modal Detail --}}
<div id="modal-detail" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:999;padding:20px;overflow-y:auto">
    <div style="max-width:520px;margin:40px auto;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2)">
        <div style="background:#16a34a;padding:20px 24px;display:flex;justify-content:space-between;align-items:center">
            <div>
                <p style="color:#dcfce7;font-size:11px;font-weight:700">DETAIL PENDAFTARAN MCU</p>
                <p id="modal-kode" style="color:#fff;font-size:18px;font-weight:900"></p>
            </div>
            <button onclick="document.getElementById('modal-detail').style.display='none'"
                    style="background:rgba(255,255,255,.2);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:16px">×</button>
        </div>
        <div id="modal-body" style="padding:24px"></div>
    </div>
</div>

{{-- Hidden data untuk modal --}}
<script>
const mcuData = {
    @foreach($pendaftarans as $p)
    {{ $p->id }}: {
        kode: "{{ $p->kode_pendaftaran }}",
        nama: "{{ addslashes($p->nama_lengkap) }}",
        nik: "{{ $p->nik ?: '-' }}",
        jk: "{{ $p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}",
        tgl_lahir: "{{ $p->tanggal_lahir?->format('d/m/Y') }}",
        no_hp: "{{ $p->no_hp }}",
        email: "{{ $p->email ?: '-' }}",
        alamat: "{{ addslashes($p->alamat ?: '-') }}",
        paket: "{{ $p->paket_label }}",
        tgl_pilihan: "{{ $p->tanggal_pilihan?->format('d/m/Y') }}",
        sesi: "{{ ucfirst($p->sesi) }}",
        catatan: "{{ addslashes($p->catatan ?: '-') }}",
        status: "{{ $p->status_label }}",
        created: "{{ $p->created_at?->format('d/m/Y H:i') }}",
    },
    @endforeach
};

function showDetail(id) {
    const d = mcuData[id];
    if (!d) return;
    document.getElementById('modal-kode').textContent = d.kode;
    const rows = [
        ['Nama Lengkap', d.nama], ['NIK', d.nik], ['Jenis Kelamin', d.jk],
        ['Tanggal Lahir', d.tgl_lahir], ['No. HP', d.no_hp], ['Email', d.email],
        ['Alamat', d.alamat], ['Paket MCU', d.paket], ['Tanggal Pilihan', d.tgl_pilihan],
        ['Sesi', d.sesi], ['Catatan', d.catatan], ['Status', d.status],
        ['Didaftarkan', d.created],
    ];
    document.getElementById('modal-body').innerHTML = rows.map(([l,v]) =>
        `<div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;gap:16px">
            <span style="font-size:12px;font-weight:600;color:#64748b;flex-shrink:0">${l}</span>
            <span style="font-size:13px;color:#0f172a;font-weight:500;text-align:right">${v}</span>
        </div>`
    ).join('');
    document.getElementById('modal-detail').style.display = 'block';
}

function updateStatus(id, status) {
    if (!confirm('Ubah status pendaftaran ini?')) return;
    fetch(`/admin/mcu/${id}/status`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status })
    }).then(r => r.json()).then(d => {
        if (d.success) {
            const el = document.createElement('div');
            el.className = 'alert alert-success';
            el.style.cssText = 'position:fixed;top:16px;right:16px;z-index:9999;min-width:280px';
            el.innerHTML = `<i class="fas fa-circle-check"></i><span>Status berhasil diperbarui</span>`;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 3000);
        }
    });
}

document.getElementById('modal-detail').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
</script>
@endsection
