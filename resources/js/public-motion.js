const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

let scrollController = null;

function markRevealTargets() {
    document.querySelectorAll('main section').forEach((section, index) => {
        section.classList.remove('motion-reveal', 'is-visible');

        if (section.dataset.motion === 'hero') {
            return;
        }

        if (index === 0 && section.querySelector('.rise')) {
            section.dataset.motion = 'hero';
            return;
        }

        section.classList.add('motion-reveal');
    });

    document.querySelectorAll('main .grid').forEach((grid) => {
        grid.classList.add('motion-stagger');

        grid.querySelectorAll(':scope > *').forEach((child, index) => {
            if (child.classList.contains('motion-reveal')) {
                return;
            }

            child.classList.add('motion-reveal');
            child.style.setProperty('--motion-delay', `${Math.min(index * 0.07, 0.42)}s`);
        });
    });
}

function observeRevealTargets() {
    if (prefersReducedMotion()) {
        document.querySelectorAll('.motion-reveal').forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.08, rootMargin: '0px 0px -6% 0px' },
    );

    document.querySelectorAll('.motion-reveal:not(.is-visible)').forEach((el) => {
        observer.observe(el);
    });
}

function initNavbarScroll() {
    scrollController?.abort();
    scrollController = new AbortController();

    const header = document.querySelector('[data-motion-header]');
    if (!header) {
        return;
    }

    const onScroll = () => {
        header.classList.toggle('is-scrolled', window.scrollY > 8);
    };

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true, signal: scrollController.signal });
}

function initSmoothAnchors() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        if (anchor.dataset.motionBound === 'true') {
            return;
        }

        anchor.dataset.motionBound = 'true';
        anchor.addEventListener('click', (event) => {
            const href = anchor.getAttribute('href');
            if (!href || href === '#') {
                return;
            }

            const target = document.querySelector(href);
            if (!target) {
                return;
            }

            event.preventDefault();
            target.scrollIntoView({ behavior: prefersReducedMotion() ? 'auto' : 'smooth', block: 'start' });
        });
    });
}

function playPageEnter() {
    const main = document.querySelector('main');
    if (!main || prefersReducedMotion()) {
        return;
    }

    main.classList.remove('motion-enter');
    void main.offsetWidth;
    main.classList.add('motion-enter');
}

export function initPublicMotion() {
    if (!document.body.classList.contains('public-site')) {
        return;
    }

    markRevealTargets();
    observeRevealTargets();
    initNavbarScroll();
    initSmoothAnchors();
    playPageEnter();
}

document.addEventListener('DOMContentLoaded', initPublicMotion);
document.addEventListener('livewire:navigated', initPublicMotion);
