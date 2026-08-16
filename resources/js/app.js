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

    const cartCountBadges = document.querySelectorAll('[data-cart-count]');
    const cartToast = document.querySelector('[data-cart-toast]');
    const cartToastMessage = cartToast?.querySelector('[data-cart-toast-message]');
    let cartToastTimer = null;

    const showCartToast = (message) => {
        if (!cartToast || !cartToastMessage) {
            return;
        }

        cartToastMessage.textContent = message;
        cartToast.classList.remove('opacity-0');

        clearTimeout(cartToastTimer);

        cartToastTimer = setTimeout(() => {
            cartToast.classList.add('opacity-0');
        }, 2600);
    };

    document.querySelectorAll('form[data-add-to-cart]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const button = form.querySelector('button[type="submit"]');
            const originalLabel = button ? button.textContent : '';

            if (button) {
                button.disabled = true;
                button.textContent = 'Đang thêm…';
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { Accept: 'application/json' },
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const payload = await response.json();

                cartCountBadges.forEach((badge) => {
                    badge.textContent = String(payload.count);
                });

                showCartToast(payload.message);
            } catch (error) {
                // Network or server failure: fall back to a normal form submit.
                form.submit();
                return;
            } finally {
                if (button) {
                    button.disabled = false;
                    button.textContent = originalLabel;
                }
            }
        });
    });
});
