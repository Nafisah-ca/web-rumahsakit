// ===== NAVBAR SCROLL EFFECT =====
const navbar = document.getElementById('navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
}

// ===== MOBILE MENU TOGGLE =====
const menuBtn = document.getElementById('menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
if (menuBtn && mobileMenu) {
    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
}

// ===== SMOOTH CLOSE MOBILE MENU ON LINK CLICK =====
document.querySelectorAll('#mobile-menu a').forEach(link => {
    link.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
    });
});

// ===== COUNTER ANIMATION =====
function animateCounter(el) {
    const target = parseInt(el.getAttribute('data-target'));
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        el.textContent = Math.floor(current).toLocaleString('id-ID');
    }, 16);
}

// ===== INTERSECTION OBSERVER FOR ANIMATIONS =====
const observerOptions = {
    threshold: 0.15,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in-up');
            entry.target.style.opacity = '1';

            // Trigger counter if it's a counter element
            const counters = entry.target.querySelectorAll('[data-target]');
            counters.forEach(counter => animateCounter(counter));

            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe all animatable sections
document.querySelectorAll('.animate-on-scroll').forEach(el => {
    el.style.opacity = '0';
    observer.observe(el);
});

// ===== STATS SECTION COUNTER =====
const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.querySelectorAll('[data-target]').forEach(counter => {
                animateCounter(counter);
            });
            statsObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.3 });

const statsSection = document.getElementById('stats-section');
if (statsSection) statsObserver.observe(statsSection);

// ===== ACTIVE NAV LINK =====
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-link');

window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop - 100;
        if (window.scrollY >= sectionTop) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('text-blue-600', 'font-semibold');
        if (link.getAttribute('href') === '#' + current) {
            link.classList.add('text-blue-600', 'font-semibold');
        }
    });
});

// ===== BACK TO TOP BUTTON =====
const backToTop = document.getElementById('back-to-top');
if (backToTop) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            backToTop.classList.remove('opacity-0', 'pointer-events-none');
            backToTop.classList.add('opacity-100');
        } else {
            backToTop.classList.add('opacity-0', 'pointer-events-none');
            backToTop.classList.remove('opacity-100');
        }
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// ===== EMERGENCY TICKER =====
const ticker = document.getElementById('emergency-ticker');
if (ticker) {
    const messages = [
        '🚨 IGD 24 Jam: (021) 555-0100',
        '📞 Pendaftaran Online: 0800-1234-5678',
        '🏥 Poliklinik Buka: Senin–Sabtu 08.00–20.00',
        '💉 Jadwal Vaksinasi: Setiap Selasa & Kamis',
        '🩺 Konsultasi Online tersedia via WhatsApp',
    ];
    let index = 0;
    setInterval(() => {
        index = (index + 1) % messages.length;
        ticker.style.opacity = '0';
        setTimeout(() => {
            ticker.textContent = messages[index];
            ticker.style.opacity = '1';
        }, 300);
    }, 4000);
    ticker.style.transition = 'opacity 0.3s ease';
}

// ===== APPOINTMENT FORM VALIDATION =====
const appointmentForm = document.getElementById('appointment-form');
if (appointmentForm) {
    appointmentForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const btn = appointmentForm.querySelector('[type=submit]');
        btn.textContent = 'Mengirim...';
        btn.disabled = true;
        setTimeout(() => {
            btn.textContent = '✓ Berhasil Dikirim!';
            btn.classList.add('bg-green-600');
            setTimeout(() => {
                btn.textContent = 'Buat Janji Temu';
                btn.disabled = false;
                btn.classList.remove('bg-green-600');
                appointmentForm.reset();
            }, 3000);
        }, 1500);
    });
}
