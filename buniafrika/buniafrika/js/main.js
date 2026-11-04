
document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
});


function initScrollReveal() {
    if (window.location.pathname.includes('/admin/')) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        const revealElements = document.querySelectorAll('.reveal');
        revealElements.forEach(el => el.classList.add('reveal-active'));
        return;
    }

    const grids = document.querySelectorAll('.bento-grid, .projects-grid, .masonry-grid, .news-layout, .timeline');
    grids.forEach(grid => {
        let items;
        if (grid.classList.contains('timeline')) {
            items = grid.querySelectorAll('.timeline-item');
        } else if (grid.classList.contains('news-layout')) {
            items = grid.querySelectorAll('.news-card');
        } else {
            items = grid.children;
        }

        Array.from(items).forEach((item, index) => {
            if (!item.classList.contains('reveal')) {
                item.classList.add('reveal');
                
                if (item.classList.contains('timeline-item-left')) {
                    item.classList.add('reveal-slide-left');
                } else if (item.classList.contains('timeline-item-right')) {
                    item.classList.add('reveal-slide-right');
                } else {
                    item.classList.add('reveal-slide-up');
                }
                
                const delay = (index % 4) * 120; 
                item.style.transitionDelay = `${delay}ms`;
            }
        });
    });

    const standalones = document.querySelectorAll('.section-header, .hero-content > *');
    standalones.forEach((el, index) => {
        if (!el.classList.contains('reveal')) {
            el.classList.add('reveal');
            el.classList.add('reveal-slide-up');
            if (el.parentElement.classList.contains('hero-content')) {
                el.style.transitionDelay = `${index * 150}ms`;
            }
        }
    });

    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -10% 0px',
        threshold: 0.05 
    };

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal-active');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    const revealElements = document.querySelectorAll('.reveal');
    revealElements.forEach(el => {
        revealObserver.observe(el);
    });
}
