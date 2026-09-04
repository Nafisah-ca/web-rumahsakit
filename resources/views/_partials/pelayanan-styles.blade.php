<style>
/* ═══════════════════════════════════════════════════════
   HALAMAN PELAYANAN — Styles lengkap
═══════════════════════════════════════════════════════ */

/* ── Hero banner ────────────────────────────────── */
.ply-page-hero {
    background: linear-gradient(135deg,#166534 0%,#00b04f 60%,#22c55e 100%);
    padding: 36px 0 32px;
    position: relative; overflow: hidden;
}
.ply-page-hero::before {
    content:''; position:absolute; inset:0;
    background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.ply-page-hero-inner { position:relative; max-width:1280px; margin:0 auto; padding:0 24px; }
.ply-page-hero h1 { font-size:clamp(20px,4vw,32px); font-weight:900; color:#fff; margin-bottom:8px; line-height:1.2; }
.ply-page-hero-breadcrumb { display:flex; flex-wrap:wrap; align-items:center; gap:6px; font-size:13px; color:rgba(255,255,255,.75); }
.ply-page-hero-breadcrumb a { color:rgba(255,255,255,.9); font-weight:600; }
.ply-page-hero-breadcrumb a:hover { color:#fff; text-decoration:underline; }
.ply-page-hero-breadcrumb .sep { color:rgba(255,255,255,.4); }

/* ── Kartu Kategori (mode semua pelayanan) ───────── */
.ply-kat-card {
    display:flex; align-items:flex-start; gap:16px;
    padding:22px 20px;
    background:#fff;
    border:1.5px solid #e2f0e8;
    border-radius:18px;
    text-decoration:none;
    color:#111827;
    transition:all .22s ease;
    position:relative; overflow:hidden;
}
.ply-kat-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:3px;
    background:linear-gradient(90deg,#00b04f,#22c55e);
    transform:scaleX(0); transform-origin:left;
    transition:transform .22s ease;
}
.ply-kat-card:hover {
    border-color:#86efac;
    box-shadow:0 10px 32px rgba(0,176,79,.14);
    transform:translateY(-4px);
    color:#111827;
}
.ply-kat-card:hover::before { transform:scaleX(1); }
.ply-kat-card-icon {
    width:52px; height:52px; flex-shrink:0;
    background:linear-gradient(135deg,#dcfce7,#bbf7d0);
    border-radius:14px;
    display:flex; align-items:center; justify-content:center;
    font-size:22px; color:#16a34a;
    transition:all .22s;
}
.ply-kat-card:hover .ply-kat-card-icon {
    background:linear-gradient(135deg,#00b04f,#22c55e);
    color:#fff;
    box-shadow:0 6px 16px rgba(0,176,79,.35);
}
.ply-kat-card-body { flex:1; min-width:0; }
.ply-kat-card-title { font-size:15px; font-weight:800; margin-bottom:6px; line-height:1.3; }
.ply-kat-card-desc { font-size:12.5px; color:#6b7280; line-height:1.6; margin-bottom:10px; }
.ply-kat-card-tags { display:flex; flex-wrap:wrap; gap:5px; }
.ply-kat-tag {
    font-size:11px; font-weight:600; color:#059669;
    background:#ecfdf5; border:1px solid #a7f3d0;
    padding:2px 8px; border-radius:20px;
}
.ply-kat-tag-more { color:#6b7280; background:#f3f4f6; border-color:#e5e7eb; }
.ply-kat-card-arrow {
    width:28px; height:28px; flex-shrink:0;
    background:#f0fdf4; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    color:#00b04f; font-size:11px;
    transition:all .22s;
}
.ply-kat-card:hover .ply-kat-card-arrow {
    background:#00b04f; color:#fff;
    box-shadow:0 4px 12px rgba(0,176,79,.3);
}

/* ── Sidebar ────────────────────────────────────── */
.ply-sidebar-wrap { position:sticky; top:88px; }
.ply-side-box { background:#fff; border:1px solid #e2f0e8; border-radius:14px; overflow:hidden; box-shadow:0 2px 12px rgba(0,176,79,.06); }
.ply-side-head { display:flex; align-items:center; gap:10px; padding:14px 18px 12px; font-weight:800; font-size:14px; color:#fff; background:linear-gradient(135deg,#166534,#00b04f); }
.ply-side-icon { width:28px; height:28px; background:rgba(255,255,255,.18); border-radius:7px; display:inline-flex; align-items:center; justify-content:center; font-size:13px; color:#fff; flex-shrink:0; }
.ply-side-list { list-style:none; margin:0; padding:8px 0 10px; }
.ply-side-list li { margin:0; }
.ply-side-link {
    display:flex; align-items:center; gap:8px;
    padding:9px 16px; font-size:13.5px; color:#374151;
    transition:all .16s ease;
    border-left:3px solid transparent;
    text-decoration:none;
}
.ply-side-link:hover { color:#00b04f; background:#f0fdf4; border-left-color:#00b04f; }
.ply-side-link.is-active { color:#00b04f; font-weight:700; background:#f0fdf4; border-left-color:#00b04f; }

/* ── Info card konten ───────────────────────────── */
.ply-info-card { background:#fff; border:1px solid #e2f0e8; border-radius:18px; box-shadow:0 6px 30px rgba(0,0,0,.06); overflow:hidden; }
.ply-info-card-band { height:4px; background:linear-gradient(90deg,#166534,#00b04f,#22c55e); }
.ply-info-card-body { padding:28px 32px 32px; }
.ply-info-label { display:inline-flex; align-items:center; gap:6px; background:#ecfdf5; border:1px solid #a7f3d0; color:#059669; font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; padding:4px 12px; border-radius:50px; margin-bottom:14px; }
.ply-info-title { font-size:clamp(18px,2.5vw,24px); font-weight:900; color:#111827; margin-bottom:14px; line-height:1.3; }
.ply-info-divider { height:3px; width:48px; background:linear-gradient(90deg,#00b04f,#22c55e); border-radius:4px; margin-bottom:20px; }
.ply-info-img { width:100%; max-height:280px; object-fit:cover; border-radius:12px; margin-bottom:20px; box-shadow:0 4px 16px rgba(0,0,0,.1); }
.ply-info-body { font-size:15px; line-height:1.85; color:#374151; }
.ply-info-dokter { display:flex; align-items:flex-start; gap:12px; margin-top:22px; padding:16px 18px; background:linear-gradient(135deg,#f0fdf4,#ecfdf5); border:1px solid #a7f3d0; border-radius:12px; font-size:14px; color:#374151; line-height:1.7; }
.ply-info-dokter-icon { width:36px; height:36px; background:#00b04f; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-size:15px; flex-shrink:0; }
.ply-info-dokter a { color:#00b04f; font-weight:800; text-decoration:underline; text-underline-offset:3px; }
.ply-info-dokter a:hover { color:#166534; }

/* ── Sub-layanan cards ──────────────────────────── */
.ply-sub-card {
    display:flex; align-items:center; gap:12px;
    padding:14px 16px;
    background:#fff;
    border:1.5px solid #e2f0e8;
    border-radius:12px;
    text-decoration:none;
    color:#111827;
    font-size:13.5px; font-weight:600;
    transition:all .18s ease;
}
.ply-sub-card:hover { border-color:#00b04f; box-shadow:0 4px 16px rgba(0,176,79,.12); color:#00b04f; transform:translateY(-2px); }
.ply-sub-icon { width:36px; height:36px; flex-shrink:0; background:#ecfdf5; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:15px; color:#16a34a; transition:all .18s; }
.ply-sub-card:hover .ply-sub-icon { background:linear-gradient(135deg,#00b04f,#22c55e); color:#fff; }
.ply-sub-name { flex:1; min-width:0; line-height:1.4; }

/* ── Responsive ─────────────────────────────────── */
@media (max-width:1023px) {
    .ply-sidebar-wrap { position:static; }
}
@media (max-width:767px) {
    .ply-info-card-body { padding:20px 18px 24px; }
    [style*="grid-template-columns:280px"] { grid-template-columns:1fr !important; }
}
</style>
