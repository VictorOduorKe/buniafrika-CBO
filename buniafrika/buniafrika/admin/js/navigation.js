/**
 * BuniAfrika Accessible Navigation Interaction Handler
 * Supports:
 * - Desktop hover with intent delay & click toggling
 * - WAI-ARIA compliant keyboard navigation (Arrows, Escape, Home, End, Tab)
 * - Mobile responsive drawer with accordion expansion & scroll locking
 * - Automatic drawer closure on link selection
 */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
        initDesktopDropdowns();
        initMobileDrawer();
    });

    /**
     * Desktop Dropdown Menus
     */
    function initDesktopDropdowns() {
        const dropdowns = document.querySelectorAll('[data-nav-dropdown]');
        if (!dropdowns.length) return;

        let activeDropdown = null;
        const closeTimers = new Map();

        const openDropdown = (container) => {
            if (activeDropdown && activeDropdown !== container) {
                closeDropdown(activeDropdown);
            }
            if (closeTimers.has(container)) {
                clearTimeout(closeTimers.get(container));
                closeTimers.delete(container);
            }

            container.classList.add('is-open');
            const toggle = container.querySelector('.nav-dropdown-toggle');
            if (toggle) toggle.setAttribute('aria-expanded', 'true');
            activeDropdown = container;
        };

        const closeDropdown = (container) => {
            if (!container) return;
            if (closeTimers.has(container)) {
                clearTimeout(closeTimers.get(container));
                closeTimers.delete(container);
            }

            container.classList.remove('is-open');
            const toggle = container.querySelector('.nav-dropdown-toggle');
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
            if (activeDropdown === container) {
                activeDropdown = null;
            }
        };

        dropdowns.forEach((container) => {
            const toggle = container.querySelector('.nav-dropdown-toggle');
            const menu = container.querySelector('.nav-dropdown-menu');
            if (!toggle || !menu) return;

            const items = Array.from(menu.querySelectorAll('[role="menuitem"]'));

            // Mouse hover with intent delay (150ms buffer to cross gap smoothly)
            container.addEventListener('mouseenter', () => {
                openDropdown(container);
            });

            container.addEventListener('mouseleave', () => {
                const timer = setTimeout(() => {
                    closeDropdown(container);
                }, 160);
                closeTimers.set(container, timer);
            });

            // Toggle on click
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (container.classList.contains('is-open')) {
                    closeDropdown(container);
                } else {
                    openDropdown(container);
                }
            });

            // Keyboard navigation on toggle button
            toggle.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown' || e.key === 'Down') {
                    e.preventDefault();
                    openDropdown(container);
                    if (items.length) items[0].focus();
                } else if (e.key === 'ArrowUp' || e.key === 'Up') {
                    e.preventDefault();
                    openDropdown(container);
                    if (items.length) items[items.length - 1].focus();
                } else if (e.key === 'Escape' || e.key === 'Esc') {
                    closeDropdown(container);
                }
            });

            // Keyboard navigation inside dropdown menu
            menu.addEventListener('keydown', (e) => {
                const currentIndex = items.indexOf(document.activeElement);

                if (e.key === 'ArrowDown' || e.key === 'Down') {
                    e.preventDefault();
                    const nextIndex = (currentIndex + 1) % items.length;
                    items[nextIndex].focus();
                } else if (e.key === 'ArrowUp' || e.key === 'Up') {
                    e.preventDefault();
                    const prevIndex = (currentIndex - 1 + items.length) % items.length;
                    items[prevIndex].focus();
                } else if (e.key === 'Home') {
                    e.preventDefault();
                    if (items.length) items[0].focus();
                } else if (e.key === 'End') {
                    e.preventDefault();
                    if (items.length) items[items.length - 1].focus();
                } else if (e.key === 'Escape' || e.key === 'Esc') {
                    e.preventDefault();
                    closeDropdown(container);
                    toggle.focus();
                } else if (e.key === 'Tab') {
                    // Close dropdown when tabbing out of it
                    setTimeout(() => {
                        if (!container.contains(document.activeElement)) {
                            closeDropdown(container);
                        }
                    }, 10);
                }
            });
        });

        // Close on click outside
        document.addEventListener('click', (e) => {
            if (activeDropdown && !activeDropdown.contains(e.target)) {
                closeDropdown(activeDropdown);
            }
        });

        // Close on focus loss
        document.addEventListener('focusin', (e) => {
            if (activeDropdown && !activeDropdown.contains(e.target)) {
                closeDropdown(activeDropdown);
            }
        });
    }

    /**
     * Mobile Navigation Drawer & Accordion Groups
     */
    function initMobileDrawer() {
        const drawer = document.getElementById('drawer');
        const overlay = document.getElementById('drawer-overlay');
        const openBtn = document.querySelector('.js-open-drawer');
        const closeBtn = document.querySelector('.js-close-drawer');

        if (!drawer || !overlay) return;

        let previousActiveElement = null;

        const openDrawer = () => {
            previousActiveElement = document.activeElement;
            drawer.classList.add('active');
            overlay.classList.add('active');
            drawer.setAttribute('aria-hidden', 'false');
            if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';

            if (closeBtn) {
                closeBtn.focus();
            }
        };

        const closeDrawer = () => {
            drawer.classList.remove('active');
            overlay.classList.remove('active');
            drawer.setAttribute('aria-hidden', 'true');
            if (openBtn) {
                openBtn.setAttribute('aria-expanded', 'false');
                if (previousActiveElement === openBtn) {
                    openBtn.focus();
                }
            }
            document.body.style.overflow = '';
        };

        if (openBtn) openBtn.addEventListener('click', openDrawer);
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
        overlay.addEventListener('click', closeDrawer);

        // Escape key closes mobile drawer
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && drawer.classList.contains('active')) {
                closeDrawer();
            }
        });

        // Mobile drawer accordion submenu toggles
        const groupToggles = drawer.querySelectorAll('.js-drawer-toggle');
        groupToggles.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const group = btn.closest('.drawer-group');
                const submenu = group ? group.querySelector('.drawer-submenu') : null;
                if (!submenu) return;

                const isExpanded = btn.getAttribute('aria-expanded') === 'true';
                if (isExpanded) {
                    btn.setAttribute('aria-expanded', 'false');
                    submenu.hidden = true;
                    submenu.classList.remove('is-expanded');
                    if (group) group.classList.remove('is-open');
                } else {
                    btn.setAttribute('aria-expanded', 'true');
                    submenu.hidden = false;
                    submenu.classList.add('is-expanded');
                    if (group) group.classList.add('is-open');
                }
            });
        });

        // Auto-close drawer whenever any link inside is clicked
        const navLinks = drawer.querySelectorAll('a');
        navLinks.forEach((link) => {
            link.addEventListener('click', () => {
                closeDrawer();
            });
        });
    }
})();
