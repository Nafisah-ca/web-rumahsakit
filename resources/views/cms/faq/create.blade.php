@extends('layouts.cms')
@php $pageTitle = 'FAQ'; $breadcrumb = 'CMS / FAQ'; @endphp
@section('content')
<div class="card card-body" style="max-width:600px">
    <p style="color:#64748b">Fitur FAQ tidak tersedia. Gunakan <a href="{{ route('cms.informasi.create') }}" style="color:#16a34a;font-weight:600">Informasi Terkini</a> sebagai pengganti.</p>
</div>
@endsection
