document.addEventListener('DOMContentLoaded', () => {
    const revealTargets = document.querySelectorAll('[data-reveal]');
    let revealObserver = null;

    if (revealTargets.length && 'IntersectionObserver' in window) {
        revealObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        revealObserver.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.16 }
        );
    }

    const applyReveal = (root = document) => {
        if (!revealObserver) {
            return;
        }

        root.querySelectorAll('[data-reveal]:not(.reveal)').forEach((target) => {
            target.classList.add('reveal');
            revealObserver.observe(target);
        });
    };

    applyReveal();

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

    const setCartCount = (count) => {
        document.querySelectorAll('[data-cart-count]').forEach((badge) => {
            badge.textContent = String(count);
        });
    };

    const submitAsJson = async (form) => {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { Accept: 'application/json' },
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        return response.json();
    };

    const withBusyButton = async (form, busyLabel, work) => {
        const button = form.querySelector('button[type="submit"]');
        const originalLabel = button ? button.textContent : '';

        if (button) {
            button.disabled = true;
            button.textContent = busyLabel;
        }

        try {
            await work();
        } finally {
            if (button) {
                button.disabled = false;
                button.textContent = originalLabel;
            }
        }
    };

    const handleAddToCart = (form) => {
        withBusyButton(form, 'Đang thêm…', async () => {
            const payload = await submitAsJson(form);
            setCartCount(payload.count);
            showCartToast(payload.message);
        }).catch(() => form.submit());
    };

    const handleCartMutation = (form) => {
        const button = form.querySelector('button[type="submit"]');
        const busyLabel = button?.textContent.trim() === 'Xóa' ? 'Đang xóa…' : 'Đang cập nhật…';

        withBusyButton(form, busyLabel, async () => {
            const payload = await submitAsJson(form);

            const cartBody = document.querySelector('[data-cart-body]');
            if (cartBody && payload.html) {
                cartBody.innerHTML = payload.html;
                applyReveal(cartBody);
            }

            setCartCount(payload.count);
            showCartToast(payload.message);
        }).catch(() => form.submit());
    };

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('form[data-add-to-cart], form[data-cart-mutate]');

        if (!form) {
            return;
        }

        event.preventDefault();

        if (form.hasAttribute('data-add-to-cart')) {
            handleAddToCart(form);
        } else {
            handleCartMutation(form);
        }
    });
});
