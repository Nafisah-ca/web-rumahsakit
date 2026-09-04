<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $pageTitle ?? 'Admin' }} — RS Sari Sehat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; }

/* ===== LAYOUT ===== */
.app-shell      { display: flex; height: 100vh; overflow: hidden; }
.sidebar        { width: 240px; background: #0f172a; display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto; transition: transform .25s ease, width .25s ease; }
.main-wrap      { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; max-width: 100%; }
.main-content   { flex: 1; overflow-y: auto; padding: 24px; }

/* ===== SIDEBAR ===== */
.sidebar-logo   { padding: 20px; border-bottom: 1px solid #1e293b; display: flex; align-items: center; gap: 12px; text-decoration: none; flex-shrink: 0; }
.sidebar-logo-icon { width: 36px; height: 36px; background: #16a34a; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.sidebar-logo-icon i { color: #fff; font-size: 14px; }
.sidebar-logo-text p { color: #fff; font-size: 13px; font-weight: 700; line-height: 1.3; }
.sidebar-logo-text span { color: #4ade80; font-size: 10px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }

.sidebar-nav    { flex: 1; padding: 12px; }
.sidebar-group  { font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.1em; text-transform: uppercase; padding: 20px 12px 6px; white-space: nowrap; overflow: hidden; }

.sidebar-link   { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 10px; color: #94a3b8; text-decoration: none; font-size: 13px; font-weight: 500; transition: background .15s, color .15s; margin-bottom: 2px; white-space: nowrap; overflow: hidden; }
.sidebar-link:hover { background: #1e293b; color: #fff; }
.sidebar-link.active { background: #16a34a; color: #fff; box-shadow: 0 4px 14px rgba(22,163,74,.3); }
.sidebar-link .icon { width: 16px; text-align: center; flex-shrink: 0; font-size: 13px; }
.sidebar-link .link-label { overflow: hidden; text-overflow: ellipsis; }
.sidebar-link .badge-count { margin-left: auto; background: #ef4444; color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 20px; flex-shrink: 0; }

.sidebar-user   { padding: 12px; border-top: 1px solid #1e293b; display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.sidebar-user-avatar { width: 32px; height: 32px; background: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.sidebar-user-avatar i { color: #fff; font-size: 12px; }
.sidebar-user-info { flex: 1; min-width: 0; overflow: hidden; }
.sidebar-user-info p { color: #fff; font-size: 12px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sidebar-user-info span { color: #64748b; font-size: 10px; }
.sidebar-logout { background: none; border: none; cursor: pointer; color: #64748b; padding: 4px; border-radius: 6px; transition: color .15s; }
.sidebar-logout:hover { color: #ef4444; }

/* ===== TOPBAR ===== */
.topbar         { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 14px 24px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; gap: 12px; }
.topbar-left    { display: flex; align-items: center; gap: 12px; min-width: 0; }
.topbar-left h1 { font-size: 18px; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.topbar-left p  { font-size: 12px; color: #94a3b8; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.topbar-right   { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
.topbar-notif   { position: relative; background: none; border: none; cursor: pointer; color: #64748b; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: background .15s; }
.topbar-notif:hover { background: #f1f5f9; color: #0f172a; }
.topbar-notif .notif-dot { position: absolute; top: 4px; right: 4px; width: 16px; height: 16px; background: #ef4444; color: #fff; font-size: 9px; font-weight: 700; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.topbar-user    { display: flex; align-items: center; gap: 8px; }
.topbar-avatar  { width: 34px; height: 34px; background: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.topbar-avatar i { color: #fff; font-size: 13px; }
.topbar-name    { font-size: 13px; font-weight: 600; color: #334155; }

/* ── Hamburger toggle button (mobile only) ── */
.sidebar-toggle {
    display: none;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    color: #475569;
    font-size: 15px;
    flex-shrink: 0;
    transition: background .15s, color .15s;
}
.sidebar-toggle:hover { background: #0f172a; color: #fff; border-color: #0f172a; }

/* ── Sidebar overlay (mobile) ── */
#sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 1001;
    backdrop-filter: blur(2px);
}

/* ===== ALERTS ===== */
.alert          { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 500; margin: 16px 24px 0; }
.alert-success  { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.alert-error    { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
.alert-close    { margin-left: auto; background: none; border: none; cursor: pointer; color: inherit; opacity: .6; padding: 0; }
.alert-close:hover { opacity: 1; }

/* ===== CARDS ===== */
.card           { background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
.card-header    { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.card-header h3 { font-size: 15px; font-weight: 700; color: #0f172a; }
.card-body      { padding: 24px; }

/* ===== BUTTONS ===== */
.btn            { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; transition: all .15s; line-height: 1; font-family: inherit; }
.btn-primary    { background: #16a34a; color: #fff; }
.btn-primary:hover { background: #15803d; }
.btn-secondary  { background: #f1f5f9; color: #475569; }
.btn-secondary:hover { background: #e2e8f0; }
.btn-danger     { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.btn-danger:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
.btn-sm         { padding: 6px 12px; font-size: 12px; }
.btn-icon       { width: 32px; height: 32px; padding: 0; justify-content: center; border-radius: 8px; }

/* ===== FORMS ===== */
.form-group     { margin-bottom: 16px; }
.form-label     { display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px; }
.form-input     { width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 13px; font-family: inherit; color: #0f172a; background: #fff; transition: border-color .15s, box-shadow .15s; outline: none; }
.form-input:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); }
.form-input[type="color"] { padding: 4px; cursor: pointer; height: 40px; }
.form-input[type="file"] { padding: 8px 12px; }
select.form-input { cursor: pointer; }
textarea.form-input { resize: vertical; }
.form-hint      { font-size: 11px; color: #94a3b8; margin-top: 4px; }
.form-error     { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; }
.form-error ul  { list-style: disc; list-style-position: inside; color: #dc2626; font-size: 13px; }
.form-row       { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-row-3     { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.form-check     { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.form-check input { width: 16px; height: 16px; accent-color: #16a34a; cursor: pointer; }
.form-check span { font-size: 13px; font-weight: 500; color: #334155; }

/* ===== TABLES ===== */
.table-wrap     { overflow-x: auto; }
table           { width: 100%; border-collapse: collapse; }
thead           { background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
th              { padding: 11px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em; white-space: nowrap; }
td              { padding: 12px 16px; font-size: 13px; color: #334155; border-bottom: 1px solid #f8fafc; }
tr:hover td     { background: #fafcff; }
tr:last-child td { border-bottom: none; }
.table-footer   { padding: 12px 20px; border-top: 1px solid #f1f5f9; }

/* ===== BADGES ===== */
.badge          { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap; }
.badge-green    { background: #dcfce7; color: #166534; }
.badge-red      { background: #fee2e2; color: #991b1b; }
.badge-blue     { background: #dbeafe; color: #1d4ed8; }
.badge-amber    { background: #fef3c7; color: #92400e; }
.badge-purple   { background: #ede9fe; color: #6d28d9; }
.badge-slate    { background: #f1f5f9; color: #475569; }
.badge-indigo   { background: #e0e7ff; color: #3730a3; }

/* ===== STATS GRID ===== */
.stats-grid     { display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; margin-bottom: 24px; }
.stat-card      { background: #fff; border-radius: 14px; padding: 18px; border: 1px solid #f1f5f9; }
.stat-icon      { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; font-size: 14px; }
.stat-value     { font-size: 24px; font-weight: 800; color: #0f172a; }
.stat-label     { font-size: 11px; color: #94a3b8; font-weight: 500; margin-top: 2px; }

/* ===== AVATAR ===== */
.avatar         { border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
.avatar-sm      { width: 32px; height: 32px; font-size: 13px; }
.avatar-md      { width: 40px; height: 40px; font-size: 15px; }
.avatar-lg      { width: 56px; height: 56px; font-size: 20px; }
.avatar-sq      { border-radius: 10px; }

/* ===== PAGINATION ===== */
.pagination { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
nav[role="navigation"] { display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; }
nav[role="navigation"] span[aria-current="page"] span,
nav[role="navigation"] span span { display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 8px; font-size: 13px; font-weight: 600; }
nav[role="navigation"] span[aria-current="page"] span { background: #16a34a; color: #fff; }
nav[role="navigation"] a { display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 8px; font-size: 13px; font-weight: 500; color: #475569; text-decoration: none; border: 1px solid #e2e8f0; transition: all .15s; }
nav[role="navigation"] a:hover { background: #f1f5f9; border-color: #cbd5e1; }

/* ===== MISC ===== */
.text-muted     { color: #94a3b8; }
.font-mono      { font-family: 'Courier New', monospace; }
.code-tag       { display: inline-block; background: #f1f5f9; color: #475569; font-family: monospace; font-size: 12px; padding: 2px 7px; border-radius: 6px; }
.divider        { border: none; border-top: 1px solid #f1f5f9; margin: 20px 0; }
.empty-state    { text-align: center; padding: 60px 20px; color: #94a3b8; }
.empty-state i  { font-size: 40px; opacity: .25; display: block; margin-bottom: 12px; }
.empty-state p  { font-size: 14px; font-weight: 500; }
.img-thumb      { border-radius: 8px; object-fit: cover; }

/* ===== PROFILE DROPDOWN ===== */
.profile-dropdown        { position: relative; }
.profile-trigger         { display: flex; align-items: center; gap: 8px; padding: 6px 10px; border-radius: 10px; cursor: pointer; border: 1px solid #e2e8f0; background: #fff; transition: background .15s, border-color .15s; user-select: none; }
.profile-trigger:hover   { background: #f8fafc; border-color: #cbd5e1; }
.profile-trigger-chevron { color: #94a3b8; font-size: 11px; transition: transform .2s; }
.profile-trigger-chevron.open { transform: rotate(180deg); }
.profile-menu            { position: absolute; right: 0; top: calc(100% + 8px); width: 230px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 8px 30px rgba(0,0,0,.1); z-index: 999; overflow: hidden; }
.profile-menu-header     { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; background: #f8fafc; }
.profile-menu-header p   { font-size: 13px; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.profile-menu-header span{ font-size: 11px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; margin-top: 2px; }
.profile-menu-item       { display: flex; align-items: center; gap: 10px; padding: 11px 16px; font-size: 13px; font-weight: 500; color: #334155; text-decoration: none; transition: background .12s; border: none; background: none; width: 100%; cursor: pointer; font-family: inherit; }
.profile-menu-item:hover { background: #f8fafc; color: #0f172a; }
.profile-menu-item i     { width: 16px; text-align: center; font-size: 13px; color: #64748b; }
.profile-menu-item:hover i { color: #16a34a; }
.profile-menu-divider    { border: none; border-top: 1px solid #f1f5f9; margin: 4px 0; }
.profile-menu-item.danger      { color: #dc2626; }
.profile-menu-item.danger i    { color: #dc2626; }
.profile-menu-item.danger:hover{ background: #fef2f2; color: #b91c1c; }

/* ===== DASHBOARD GRIDS ===== */
.dash-main-grid  { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 24px; }
.dash-quick-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }

/* ===== RESPONSIVE — TABLET (1024px) ===== */
@media (max-width: 1024px) {
    .stats-grid      { grid-template-columns: repeat(3, 1fr); }
    .form-row        { grid-template-columns: 1fr; }
    .dash-quick-grid { grid-template-columns: repeat(2, 1fr); }
}

/* ===== RESPONSIVE — MOBILE (1023px ke bawah) ===== */
@media (max-width: 1023px) {
    /* Sidebar offscreen by default */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1100;
        transform: translateX(-100%);
        width: 260px;
        box-shadow: 4px 0 24px rgba(0,0,0,.25);
    }
    .sidebar.open {
        transform: translateX(0);
    }
    /* Overlay muncul saat sidebar open */
    #sidebar-overlay.open {
        display: block;
    }
    /* Hamburger tampil */
    .sidebar-toggle {
        display: inline-flex;
    }
    /* Main ambil full width */
    .main-wrap {
        width: 100%;
    }
    /* Topbar */
    .topbar { padding: 12px 16px; }
    .topbar-left h1 { font-size: 15px; }
    .topbar-name { display: none; }
    /* Content */
    .main-content { padding: 16px; }
    /* Form grid → single column */
    .form-row, .form-row-3 { grid-template-columns: 1fr !important; }
    /* Stats grid → 2 kolom */
    .stats-grid      { grid-template-columns: repeat(2, 1fr) !important; }
    /* Dashboard grids */
    .dash-main-grid  { grid-template-columns: 1fr !important; }
    .dash-quick-grid { grid-template-columns: repeat(2, 1fr) !important; }
}

/* ===== RESPONSIVE — SMALL MOBILE (479px ke bawah) ===== */
@media (max-width: 479px) {
    .stats-grid { grid-template-columns: 1fr !important; }
    .topbar-left h1 { font-size: 14px; }
}

/* ===== GLOBAL MOBILE RESPONSIVE — TABLE AS CARD ===== */
@media (max-width: 767px) {
    .main-content {
        padding: 12px !important;
        overflow-x: hidden !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .main-content > * {
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    .card, .card-body {
        border-radius: 10px !important;
        overflow: hidden !important;
    }
    .card-header {
        flex-wrap: wrap !important;
        gap: 8px !important;
        padding: 12px 14px !important;
        align-items: flex-start !important;
    }
    .card-header form,
    .card-header > div[style*="display:flex"] {
        flex-wrap: wrap !important;
        width: 100% !important;
        gap: 6px !important;
    }
    .card-header form input,
    .card-header form select {
        flex: 1 1 120px !important;
        min-width: 0 !important;
        width: auto !important;
        max-width: 100% !important;
    }
    .card-header .btn,
    .card-header a.btn {
        flex-shrink: 0 !important;
    }

    /* Table → card list per row */
    .table-wrap { overflow-x: visible !important; }
    table, tbody, td, tr { display: block !important; width: 100% !important; }
    thead { display: none !important; }
    table { min-width: 0 !important; border-collapse: separate !important; border-spacing: 0 !important; }
    tbody tr {
        margin-bottom: 10px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        background: #fff !important;
        box-shadow: 0 1px 4px rgba(0,0,0,.05) !important;
    }
    tbody td {
        padding: 9px 14px !important;
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        font-size: 13px !important;
        text-align: left !important;
        white-space: normal !important;
        word-break: break-word !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 8px !important;
        overflow: hidden !important;
    }
    tbody td:last-child {
        border-bottom: none !important;
        justify-content: flex-end !important;
        flex-wrap: wrap !important;
        gap: 6px !important;
    }
    tbody td > * { max-width: 100% !important; min-width: 0 !important; flex-shrink: 1 !important; }
    tbody td > div { overflow: hidden !important; word-break: break-word !important; max-width: 100% !important; }
    tbody td img { max-width: 60px !important; height: auto !important; flex-shrink: 0 !important; }
    tbody td[data-label]::before {
        content: attr(data-label);
        font-size: 10px !important;
        font-weight: 700 !important;
        color: #94a3b8 !important;
        text-transform: uppercase !important;
        letter-spacing: .06em !important;
        flex-shrink: 0 !important;
        margin-right: 4px !important;
    }
    tbody td > .badge, tbody td > form, tbody td > a.btn { flex-shrink: 0 !important; }
    td > div[style*="display:flex"] { flex-wrap: wrap !important; max-width: 100% !important; gap: 6px !important; }
    td, td * { white-space: normal !important; }

    /* Grid overrides */
    [style*="grid-template-columns:repeat(5"],
    [style*="grid-template-columns: repeat(5"] { grid-template-columns: repeat(2, 1fr) !important; }
    [style*="grid-template-columns:1fr 1fr"],
    [style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
    [style*="grid-template-columns:1fr 300px"],
    [style*="grid-template-columns: 1fr 300px"],
    [style*="grid-template-columns:220px 1fr"],
    [style*="grid-template-columns: 220px 1fr"],
    [style*="grid-template-columns:1fr 380px"],
    [style*="grid-template-columns: 1fr 380px"] { grid-template-columns: 1fr !important; }

    /* Stats */
    .stats-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 8px !important; }
    .stat-card  { padding: 14px 12px !important; }
    .stat-value { font-size: 20px !important; }

    /* Dashboard grids */
    .dash-main-grid  { grid-template-columns: 1fr !important; gap: 16px !important; }
    .dash-quick-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }

    /* Form rows */
    .form-row, .form-row-3 { grid-template-columns: 1fr !important; }

    /* Sticky → relative di mobile */
    [style*="position:sticky"],
    [style*="position: sticky"] { position: relative !important; top: auto !important; }

    /* Topbar */
    .topbar { padding: 10px 12px !important; }
    .topbar-left h1 { font-size: 14px !important; }
    .topbar-name { display: none !important; }

    /* Alert */
    .alert { margin: 8px 12px 0 !important; padding: 10px 12px !important; }

    /* Pagination */
    nav[role="navigation"] { justify-content: center !important; gap: 4px !important; }
}
</style>
@stack('styles')
</head>
<body>

{{-- ===== SIDEBAR OVERLAY (mobile) ===== --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="app-shell">
    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon"><i class="fas fa-hospital-alt"></i></div>
            <div class="sidebar-logo-text">
                <p>RS Sari Sehat</p>
                <span>Admin Panel</span>
            </div>
        </a>

        <nav class="sidebar-nav">
            <div class="sidebar-group">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high icon"></i> <span class="link-label">Dashboard</span>
            </a>

            <div class="sidebar-group">Operasional</div>
            <a href="{{ route('admin.booking') }}" class="sidebar-link {{ request()->routeIs('admin.booking*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check icon"></i> <span class="link-label">Booking & Janji Temu</span>
                @php $pending = \App\Models\JanjiTemu::where('status','pending')->count() @endphp
                @if($pending > 0)<span class="badge-count">{{ $pending }}</span>@endif
            </a>
            <a href="{{ route('admin.pasien') }}" class="sidebar-link {{ request()->routeIs('admin.pasien*') ? 'active' : '' }}">
                <i class="fas fa-bed-pulse icon"></i> <span class="link-label">Data Pasien</span>
            </a>
            <a href="{{ route('admin.jadwal') }}" class="sidebar-link {{ request()->routeIs('admin.jadwal*') ? 'active' : '' }}">
                <i class="fas fa-clock icon"></i> <span class="link-label">Jadwal Dokter</span>
            </a>
            <a href="{{ route('admin.laporan') }}" class="sidebar-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                <i class="fas fa-chart-column icon"></i> <span class="link-label">Laporan</span>
            </a>
            <a href="{{ route('admin.pengunjung') }}" class="sidebar-link {{ request()->routeIs('admin.pengunjung') ? 'active' : '' }}">
                <i class="fas fa-chart-line icon"></i> <span class="link-label">Statistik Pengunjung</span>
            </a>
            <a href="{{ route('admin.mcu') }}" class="sidebar-link {{ request()->routeIs('admin.mcu*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-check icon"></i> <span class="link-label">Pendaftaran MCU</span>
                @php $mcuMenunggu = \App\Models\PendaftaranMcu::where('status','menunggu')->count(); @endphp
                @if($mcuMenunggu > 0)<span class="badge-count">{{ $mcuMenunggu }}</span>@endif
            </a>

            <div class="sidebar-group">Master Data</div>
            <a href="{{ route('admin.dokter') }}" class="sidebar-link {{ request()->routeIs('admin.dokter*') ? 'active' : '' }}">
                <i class="fas fa-user-doctor icon"></i> <span class="link-label">Dokter</span>
            </a>
            <a href="{{ route('admin.spesialisasi') }}" class="sidebar-link {{ request()->routeIs('admin.spesialisasi*') ? 'active' : '' }}">
                <i class="fas fa-stethoscope icon"></i> <span class="link-label">Spesialisasi</span>
            </a>
            <a href="{{ route('admin.penjamin') }}" class="sidebar-link {{ request()->routeIs('admin.penjamin*') || request()->routeIs('admin.tipe-penjamin*') ? 'active' : '' }}">
                <i class="fas fa-shield-halved icon"></i> <span class="link-label">Penjamin</span>
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users icon"></i> <span class="link-label">Manajemen User</span>
            </a>

            <div class="sidebar-group">Lainnya</div>
            <a href="{{ route('admin.cms-login') }}" class="sidebar-link {{ request()->routeIs('admin.cms-login*') || request()->routeIs('cms.*') ? 'active' : '' }}">
                <i class="fas fa-pen-nib icon"></i> <span class="link-label">Akses CMS</span>
            </a>
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                <i class="fas fa-arrow-up-right-from-square icon"></i> <span class="link-label">Lihat Website</span>
            </a>
        </nav>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="main-wrap">
        <header class="topbar">
            <div class="topbar-left">
                {{-- Hamburger — hanya tampil di mobile --}}
                <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Buka menu">
                    <i class="fas fa-bars" id="hamburger-icon"></i>
                </button>
                <div>
                    <h1>{{ $pageTitle ?? 'Dashboard' }}</h1>
                    @isset($breadcrumb)<p>{{ $breadcrumb }}</p>@endisset
                </div>
            </div>
            <div class="topbar-right">
                @php
                    $notifCount   = \App\Models\JanjiTemu::where('status', 'pending')
                        ->whereDate('tanggal_booking', today())
                        ->count();
                    $notifBookings = \App\Models\JanjiTemu::with(['pasien.user','jadwalDokter.dokter'])
                        ->where('status', 'pending')
                        ->whereDate('tanggal_booking', today())
                        ->orderByDesc('created_tm')
                        ->limit(5)
                        ->get();
                @endphp

                {{-- Notifikasi Lonceng --}}
                <div style="position:relative" x-data="{ open: false }" @click.outside="open = false">
                    <button class="topbar-notif" @click="open = !open">
                        <i class="fas fa-bell"></i>
                        @if($notifCount > 0)<span class="notif-dot">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>@endif
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="display:none;position:absolute;top:calc(100% + 8px);right:0;width:300px;background:#fff;border-radius:14px;box-shadow:0 8px 30px rgba(0,0,0,.12);border:1px solid #f1f5f9;z-index:999">

                        {{-- Header --}}
                        <div style="padding:14px 16px 10px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
                            <p style="font-size:13px;font-weight:700;color:#0f172a">Booking Pending Hari Ini</p>
                            @if($notifCount > 0)
                            <span style="background:#fef3c7;color:#92400e;font-size:11px;font-weight:700;padding:2px 8px;border-radius:99px">{{ $notifCount }} menunggu</span>
                            @endif
                        </div>

                        {{-- List --}}
                        <div style="max-height:280px;overflow-y:auto">
                            @forelse($notifBookings as $nb)
                            <a href="{{ route('admin.booking.show', $nb) }}"
                               style="display:flex;align-items:center;gap:10px;padding:10px 16px;text-decoration:none;border-bottom:1px solid #f8fafc;transition:background .1s"
                               onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <div style="width:36px;height:36px;background:#fef3c7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <i class="fas fa-clock" style="color:#d97706;font-size:13px"></i>
                                </div>
                                <div style="min-width:0">
                                    <p style="font-size:12px;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                        {{ $nb->pasien?->user?->nama ?? '-' }}
                                    </p>
                                    <p style="font-size:11px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                        {{ $nb->jadwalDokter?->dokter?->nama_dokter ?? '-' }}
                                        @if($nb->jadwalDokter?->jam_mulai)
                                        · {{ substr($nb->jadwalDokter->jam_mulai, 0, 5) }} WIB
                                        @endif
                                    </p>
                                </div>
                                <span style="font-size:10px;font-weight:700;background:#fef3c7;color:#92400e;padding:2px 7px;border-radius:6px;flex-shrink:0">Pending</span>
                            </a>
                            @empty
                            <div style="padding:24px 16px;text-align:center">
                                <i class="fas fa-check-circle" style="color:#16a34a;font-size:24px;margin-bottom:8px;display:block"></i>
                                <p style="font-size:12px;color:#64748b">Tidak ada booking pending hari ini</p>
                            </div>
                            @endforelse
                        </div>

                        {{-- Footer --}}
                        <div style="padding:10px 16px;border-top:1px solid #f1f5f9">
                            <a href="{{ route('admin.booking', ['status'=>'pending']) }}"
                               style="display:block;text-align:center;font-size:12px;font-weight:600;color:#16a34a;text-decoration:none;padding:6px;border-radius:8px;transition:background .1s"
                               onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='transparent'">
                                Lihat Semua Booking Pending <i class="fas fa-arrow-right" style="font-size:11px"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Profile Dropdown --}}
                <div class="profile-dropdown" x-data="{ open: false }" @click.outside="open = false">
                    <div class="profile-trigger" @click="open = !open">
                        @if(Auth::user()->foto)
                            <img src="{{ Storage::url(Auth::user()->foto) }}"
                                 style="width:34px;height:34px;border-radius:50%;object-fit:cover;flex-shrink:0"
                                 alt="{{ Auth::user()->nama }}">
                        @else
                            <div class="topbar-avatar">
                                <span style="color:#fff;font-size:13px;font-weight:700">{{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}</span>
                            </div>
                        @endif
                        <span class="topbar-name">{{ Str::words(Auth::user()->nama, 2, '') }}</span>
                        <i class="fas fa-chevron-down profile-trigger-chevron" :class="{ open: open }"></i>
                    </div>
                    <div class="profile-menu" x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         style="display:none">
                        <div class="profile-menu-header">
                            <p>{{ Auth::user()->nama }}</p>
                            <span>{{ Auth::user()->email }}</span>
                        </div>
                        <a href="{{ route('admin.profile') }}" class="profile-menu-item">
                            <i class="fas fa-user-pen"></i> Profile Saya
                        </a>
                        <a href="{{ route('admin.setting.password') }}" class="profile-menu-item">
                            <i class="fas fa-lock"></i> Ganti Password
                        </a>
                        <hr class="profile-menu-divider">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="profile-menu-item danger">
                                <i class="fas fa-right-from-bracket"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        @if(session('success'))
        <div class="alert alert-success" id="alert-success">
            <i class="fas fa-circle-check"></i>
            <span>{{ session('success') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-xmark"></i></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <main class="main-content">
            @yield('content')
        </main>
    </div>
</div>

<script>
// ===== Alert auto dismiss =====
setTimeout(() => { document.getElementById('alert-success')?.remove(); }, 4500);

// ===== Sidebar mobile toggle =====
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebar-overlay').classList.add('open');
    document.getElementById('hamburger-icon').classList.remove('fa-bars');
    document.getElementById('hamburger-icon').classList.add('fa-xmark');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('open');
    document.getElementById('hamburger-icon').classList.remove('fa-xmark');
    document.getElementById('hamburger-icon').classList.add('fa-bars');
    document.body.style.overflow = '';
}

function toggleSidebar() {
    if (document.getElementById('sidebar').classList.contains('open')) {
        closeSidebar();
    } else {
        openSidebar();
    }
}

// Tutup sidebar saat klik link di mobile
document.querySelectorAll('.sidebar-link').forEach(function(link) {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 1023) {
            closeSidebar();
        }
    });
});

// Tutup sidebar saat resize ke desktop
window.addEventListener('resize', function() {
    if (window.innerWidth > 1023) {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('open');
        document.body.style.overflow = '';
        var icon = document.getElementById('hamburger-icon');
        if (icon) {
            icon.classList.remove('fa-xmark');
            icon.classList.add('fa-bars');
        }
    }
});
</script>
@stack('scripts')
</body>
</html>
