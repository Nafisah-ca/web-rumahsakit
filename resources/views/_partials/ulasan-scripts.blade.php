{{-- Partial: _partials/ulasan-scripts.blade.php
     Taruh di @push('scripts') halaman yang pakai komponen ulasan v2 --}}
<script>
(function() {
    /* Animasi bar rating saat masuk viewport */
    var summaryWrap = document.querySelector('.ulasan-summary-wrap');
    if (summaryWrap) {
        var barObs = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) {
                if (e.isIntersecting) {
                    e.target.querySelectorAll('.ulasan-bar-fill').forEach(function(bar) {
                        setTimeout(function() { bar.style.width = bar.dataset.pct + '%'; }, 150);
                    });
                    barObs.unobserve(e.target);
                }
            });
        }, { threshold: 0.3 });
        barObs.observe(summaryWrap);
    }

    /* Staggered entrance kartu ulasan */
    var grid = document.querySelector('.ulasan-grid');
    if (grid) {
        var cardObs = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) {
                if (e.isIntersecting) {
                    e.target.querySelectorAll('.ulasan-card-v2').forEach(function(c) {
                        c.classList.add('card-visible');
                    });
                    cardObs.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        cardObs.observe(grid);
    }
})();
</script>
