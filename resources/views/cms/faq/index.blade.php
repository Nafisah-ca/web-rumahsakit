@extends('layouts.cms')
@php $pageTitle = 'FAQ'; $breadcrumb = 'CMS / FAQ'; @endphp
@section('content')
<div class="card card-body" style="max-width:600px">
    <div style="text-align:center;padding:40px 20px">
        <i class="fas fa-circle-info" style="font-size:48px;color:#94a3b8;margin-bottom:16px;display:block"></i>
        <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:8px">Fitur FAQ</h3>
        <p style="font-size:14px;color:#64748b;line-height:1.6">
            Fitur FAQ tidak tersedia pada versi database saat ini.<br>
            Gunakan <strong>Informasi Terkini</strong> untuk menampilkan konten FAQ kepada pengunjung.
        </p>
        <a href="{{ route('cms.informasi') }}" class="btn btn-primary" style="margin-top:20px;display:inline-flex">
            <i class="fas fa-arrow-right"></i> Ke Informasi Terkini
        </a>
    </div>
</div>
@endsection
