document.addEventListener('DOMContentLoaded', () => {
    const revealTargets = document.querySelectorAll('[data-reveal]');

    if (revealTargets.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.16 }
        );

        revealTargets.forEach((target, index) => {
            target.classList.add('reveal');
            target.style.transitionDelay = `${Math.min(index * 70, 280)}ms`;
            observer.observe(target);
        });
    }

    const header = document.querySelector('[data-site-header]');
    const mobileToggle = document.querySelector('[data-mobile-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    if (header) {
        const updateHeader = () => {
            header.classList.toggle('bg-white/88', window.scrollY > 12);
            header.classList.toggle('shadow-[0_16px_45px_rgba(24,22,17,0.08)]', window.scrollY > 12);
            header.classList.toggle('backdrop-blur-xl', window.scrollY > 12);
        };

        updateHeader();
        window.addEventListener('scroll', updateHeader, { passive: true });
    }

    if (mobileToggle && mobileMenu) {
        const iconOpen = mobileToggle.querySelector('[data-icon-open]');
        const iconClose = mobileToggle.querySelector('[data-icon-close]');

        mobileToggle.addEventListener('click', () => {
            const isOpen = mobileMenu.dataset.open === 'true';
            mobileMenu.dataset.open = String(!isOpen);
            mobileMenu.classList.toggle('hidden', isOpen);
            mobileToggle.setAttribute('aria-expanded', String(!isOpen));
            mobileToggle.setAttribute('aria-label', isOpen ? 'Mở menu điều hướng' : 'Đóng menu điều hướng');

            if (iconOpen && iconClose) {
                iconOpen.classList.toggle('hidden', !isOpen);
                iconClose.classList.toggle('hidden', isOpen);
            }
        });
    }
});
