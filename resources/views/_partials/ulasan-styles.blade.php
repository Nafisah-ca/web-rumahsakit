{{-- Partial: _partials/ulasan-styles.blade.php
     Include sekali di @push('styles') pada halaman yang pakai komponen ulasan v2 --}}
<style>
/* ── ULASAN SHARED STYLES ─────────────────────────────── */
.ulasan-summary-wrap {
    display: flex;
    align-items: stretch;
    gap: 16px;
    background: linear-gradient(135deg,#00521f,#00b04f);
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 32px;
    flex-wrap: wrap;
}
.ulasan-score-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 100px;
    flex-shrink: 0;
}
.ulasan-score-num  { font-size:52px;font-weight:900;color:#fff;line-height:1;letter-spacing:-3px; }
.ulasan-score-stars{ display:flex;gap:3px;margin:6px 0 4px; }
.ulasan-score-stars i { font-size:13px; }
.ulasan-score-label{ font-size:11px;color:rgba(255,255,255,.7);font-weight:600; }

.ulasan-bars { flex:1;min-width:160px;display:flex;flex-direction:column;justify-content:center;gap:6px; }
.ulasan-bar-row   { display:flex;align-items:center;gap:7px; }
.ulasan-bar-num   { font-size:11px;font-weight:700;color:rgba(255,255,255,.8);width:12px;text-align:right;flex-shrink:0; }
.ulasan-bar-track { flex:1;height:7px;background:rgba(255,255,255,.2);border-radius:999px;overflow:hidden; }
.ulasan-bar-fill  { height:100%;border-radius:999px;transition:width 1s cubic-bezier(.4,0,.2,1); }
.ulasan-bar-count { font-size:11px;color:rgba(255,255,255,.65);width:20px;flex-shrink:0; }

.ulasan-cta-btn {
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    gap:6px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);
    color:#fff;border-radius:14px;padding:14px 20px;text-decoration:none;
    transition:background .15s;flex-shrink:0;text-align:center;
}
.ulasan-cta-btn:hover { background:rgba(255,255,255,.25); }

/* Banyak kartu ukuran sama, tidak terlalu tinggi */
.ulasan-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 14px;
    align-items: stretch;
}

/* Kartu */
.ulasan-card-v2 {
    background:#fff;border-radius:16px;border:1px solid #f0f0f0;
    box-shadow:0 2px 12px rgba(0,0,0,.05);padding:14px 16px;
    display:flex;flex-direction:column;position:relative;overflow:hidden;
    opacity:0;transform:translateY(16px);
    transition:opacity .4s ease calc(var(--delay,0ms)),
               transform .4s ease calc(var(--delay,0ms)),
               box-shadow .2s ease, border-color .2s ease;
    width: 100%;
    height: 148px;
}
.ulasan-card-v2.card-visible { opacity:1;transform:translateY(0); }
.ulasan-card-v2:hover { box-shadow:0 10px 30px rgba(0,0,0,.1);border-color:#d1fae5;transform:translateY(-4px); }

.ulasan-quote {
    position:absolute;top:12px;right:16px;font-size:36px;line-height:1;
    color:#f0fdf4;font-family:Georgia,serif;pointer-events:none;user-select:none;
}
.ulasan-avatar {
    width:42px;height:42px;border-radius:12px;border:2px solid;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;font-size:17px;font-weight:900;letter-spacing:-1px;
}
.ulasan-card-head { display:flex;align-items:flex-start;gap:11px;margin-bottom:8px; }
.ulasan-meta      { flex:1;min-width:0; }
.ulasan-nama      { font-size:13px;font-weight:800;color:#111;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:150px; }
.ulasan-rbadge {
    display:inline-flex;align-items:center;gap:4px;
    font-size:11px;font-weight:800;padding:2px 8px;border-radius:999px;border:1px solid;
    white-space:nowrap;flex-shrink:0;
}
.ulasan-stars-row { display:flex;align-items:center;gap:2px;margin-top:4px; }
.ulasan-date      { font-size:10px;color:#9ca3af;margin-left:6px; }
.ulasan-judul     { font-size:13px;font-weight:700;color:#1e293b;margin-bottom:4px;line-height:1.4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.ulasan-isi {
    font-size:12px;color:#6b7280;line-height:1.55;flex:1;min-height:0;
    display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
}
</style>
