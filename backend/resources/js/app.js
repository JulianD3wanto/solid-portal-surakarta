const siteHeader = document.querySelector('[data-site-header]');
window.addEventListener('scroll', () => siteHeader?.classList.toggle('header-compact', window.scrollY > 40), { passive: true });

const slideshow = document.querySelector('[data-solo-slideshow]');
if (slideshow) {
    const slides = [...slideshow.querySelectorAll('.solo-slide')];
    const dots = [...slideshow.querySelectorAll('[data-slide-dot]')];
    let currentSlide = 0;
    const showSlide = (index) => {
        currentSlide = index;
        slides.forEach((slide, i) => slide.classList.toggle('opacity-100', i === index));
        slides.forEach((slide, i) => slide.classList.toggle('opacity-0', i !== index));
        dots.forEach((dot, i) => dot.classList.toggle('bg-white', i === index));
        dots.forEach((dot, i) => dot.classList.toggle('bg-white/50', i !== index));
    };
    dots.forEach((dot, index) => dot.addEventListener('click', () => showSlide(index)));
    showSlide(0);
    setInterval(() => showSlide((currentSlide + 1) % slides.length), 5000);
}


const dateElement = document.querySelector('[data-current-date]');
const timeElement = document.querySelector('[data-current-time]');

const updateClock = () => {
    const now = new Date();
    dateElement && (dateElement.textContent = new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric', timeZone: 'Asia/Jakarta' }).format(now));
    timeElement && (timeElement.textContent = new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' }).format(now));
};

updateClock();
setInterval(updateClock, 1000);


const menuButton = document.querySelector('[data-menu-toggle]');
const menu = document.querySelector('[data-mobile-menu]');

menuButton?.addEventListener('click', () => {
    const expanded = menuButton.getAttribute('aria-expanded') === 'true';
    menuButton.setAttribute('aria-expanded', String(!expanded));
    menu?.classList.toggle('hidden', expanded);
});

const scrollTopButton = document.querySelector('[data-scroll-top]');
if (scrollTopButton) {
    const toggleScrollTop = () => scrollTopButton.classList.toggle('is-visible', window.scrollY > 300);
    window.addEventListener('scroll', toggleScrollTop, { passive: true });
    toggleScrollTop();
    scrollTopButton.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

// ===== Accessibility widget =====
const a11yPanel = document.querySelector('[data-a11y-panel]');
const a11yToggles = [...document.querySelectorAll('[data-a11y-toggle]')];
const a11yLive = document.querySelector('[data-a11y-live]');
const A11Y_FLAGS = ['contrast', 'links', 'bigtext', 'spacing', 'motion', 'images', 'dyslexia', 'cursor'];
const A11Y_LABELS = { contrast: 'Kontras tinggi', links: 'Tandai link', bigtext: 'Teks lebih besar', spacing: 'Jarak teks', motion: 'Animasi dijeda', images: 'Gambar disembunyikan', dyslexia: 'Font disleksia', cursor: 'Kursor besar' };

const a11yState = (() => {
    try { return JSON.parse(localStorage.getItem('a11y') || '{}'); } catch { return {}; }
})();

const applyA11y = (key, on) => {
    document.documentElement.classList.toggle(`a11y-${key}`, on);
    document.querySelector(`[data-a11y="${key}"]`)?.setAttribute('aria-pressed', String(on));
    a11yState[key] = on;
    localStorage.setItem('a11y', JSON.stringify(a11yState));
    if (a11yLive) a11yLive.textContent = `${A11Y_LABELS[key]}: ${on ? 'aktif' : 'nonaktif'}`;
};

A11Y_FLAGS.forEach((key) => { if (a11yState[key]) applyA11y(key, true); });

document.querySelectorAll('[data-a11y]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const key = btn.dataset.a11y;
        applyA11y(key, !document.documentElement.classList.contains(`a11y-${key}`));
    });
});

document.querySelector('[data-a11y-reset]')?.addEventListener('click', () => {
    A11Y_FLAGS.forEach((key) => applyA11y(key, false));
    if (a11yLive) a11yLive.textContent = 'Semua pengaturan direset';
});

const setA11yOpen = (open) => {
    if (!a11yPanel) return;
    a11yPanel.hidden = !open;
    a11yToggles.forEach((t) => { if (t.hasAttribute('aria-expanded')) t.setAttribute('aria-expanded', String(open)); });
};
const toggleA11y = () => setA11yOpen(a11yPanel?.hidden ?? true);
a11yToggles.forEach((btn) => btn.addEventListener('click', toggleA11y));

document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && (e.key === 'u' || e.key === 'U')) { e.preventDefault(); toggleA11y(); }
    if (e.key === 'Escape' && a11yPanel && !a11yPanel.hidden) setA11yOpen(false);
});
// ===== end accessibility widget =====

const newsCarousel = document.querySelector('[data-news-carousel]');
if (newsCarousel) {
    const track = newsCarousel.querySelector('[data-news-track]');
    const slides = [...track.children];
    const prevBtn = newsCarousel.querySelector('[data-news-prev]');
    const nextBtn = newsCarousel.querySelector('[data-news-next]');
    const dotsWrap = document.querySelector('[data-news-dots]');
    let index = 0;
    let perView = 3;

    const computePerView = () => (window.innerWidth <= 640 ? 1 : window.innerWidth <= 1024 ? 2 : 3);
    const maxIndex = () => Math.max(0, slides.length - perView);

    const renderDots = () => {
        if (!dotsWrap) return;
        dotsWrap.innerHTML = '';
        for (let i = 0; i <= maxIndex(); i++) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'news-dot' + (i === index ? ' is-active' : '');
            dot.setAttribute('aria-label', `Ke berita ${i + 1}`);
            dot.addEventListener('click', () => goTo(i));
            dotsWrap.appendChild(dot);
        }
    };

    const apply = () => {
        const step = slides[0] ? slides[0].getBoundingClientRect().width + 24 : 0;
        track.style.transform = `translateX(${-index * step}px)`;
        prevBtn && (prevBtn.disabled = index <= 0);
        nextBtn && (nextBtn.disabled = index >= maxIndex());
        [...(dotsWrap?.children ?? [])].forEach((d, i) => d.classList.toggle('is-active', i === index));
    };

    const goTo = (i) => { index = Math.min(Math.max(i, 0), maxIndex()); apply(); };
    prevBtn?.addEventListener('click', () => goTo(index - 1));
    nextBtn?.addEventListener('click', () => goTo(index + 1));

    const refresh = () => {
        const nextPerView = computePerView();
        if (nextPerView !== perView) perView = nextPerView;
        index = Math.min(index, maxIndex());
        renderDots();
        apply();
    };
    window.addEventListener('resize', refresh);

    // Drag / swipe support
    let startX = 0, currentX = 0, dragging = false;
    const onDown = (e) => { dragging = true; startX = (e.touches ? e.touches[0].clientX : e.clientX); currentX = startX; track.classList.add('is-dragging'); };
    const onMove = (e) => {
        if (!dragging) return;
        currentX = (e.touches ? e.touches[0].clientX : e.clientX);
        const base = -(index * (slides[0] ? slides[0].getBoundingClientRect().width + 24 : 0));
        track.style.transform = `translateX(${base + (currentX - startX)}px)`;
    };
    const onUp = () => {
        if (!dragging) return;
        dragging = false;
        track.classList.remove('is-dragging');
        const delta = currentX - startX;
        if (Math.abs(delta) > 50) goTo(index + (delta < 0 ? 1 : -1));
        else apply();
    };
    track.addEventListener('mousedown', onDown);
    window.addEventListener('mousemove', onMove);
    window.addEventListener('mouseup', onUp);
    track.addEventListener('touchstart', onDown, { passive: true });
    track.addEventListener('touchmove', onMove, { passive: true });
    track.addEventListener('touchend', onUp);

    refresh();
}
