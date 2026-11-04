/**
 * BuniAfrika Web Accessibility Widget Controller
 * Pure Vanilla JavaScript - WCAG 2.2 Level AA Compatible
 */
(function () {
    'use strict';

    const STORAGE_KEY = 'buniafrica_a11y_prefs';

    const SCALE_MAP = {
        '-1': { percent: '90%', class: 'a11y-text-step-m1' },
        '0':  { percent: '100%', class: '' },
        '1':  { percent: '110%', class: 'a11y-text-step-p1' },
        '2':  { percent: '120%', class: 'a11y-text-step-p2' },
        '3':  { percent: '130%', class: 'a11y-text-step-p3' },
        '4':  { percent: '140%', class: 'a11y-text-step-p4' }
    };

    const MIN_SCALE = -1;
    const MAX_SCALE = 4;

    const defaultPrefs = {
        textScale: 0,
        highContrast: false,
        grayscale: false,
        highlightLinks: false,
        readableFont: false
    };

    let inMemoryPrefs = Object.assign({}, defaultPrefs);

    function isStorageAvailable() {
        try {
            const testKey = '__a11y_test__';
            localStorage.setItem(testKey, '1');
            localStorage.removeItem(testKey);
            return true;
        } catch (e) {
            return false;
        }
    }

    const storageAvailable = isStorageAvailable();

    function loadPrefs() {
        if (!storageAvailable) {
            return Object.assign({}, inMemoryPrefs);
        }
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return Object.assign({}, defaultPrefs);
            const parsed = JSON.parse(raw);
            const prefs = Object.assign({}, defaultPrefs);

            if (typeof parsed.textScale === 'number' && parsed.textScale >= MIN_SCALE && parsed.textScale <= MAX_SCALE) {
                prefs.textScale = Math.round(parsed.textScale);
            }
            if (typeof parsed.highContrast === 'boolean') prefs.highContrast = parsed.highContrast;
            if (typeof parsed.grayscale === 'boolean') prefs.grayscale = parsed.grayscale;
            if (typeof parsed.highlightLinks === 'boolean') prefs.highlightLinks = parsed.highlightLinks;
            if (typeof parsed.readableFont === 'boolean') prefs.readableFont = parsed.readableFont;

            inMemoryPrefs = Object.assign({}, prefs);
            return prefs;
        } catch (e) {
            return Object.assign({}, defaultPrefs);
        }
    }

    function savePrefs(prefs) {
        inMemoryPrefs = Object.assign({}, prefs);
        if (!storageAvailable) return;
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(prefs));
        } catch (e) {
            // Ignore storage write errors (e.g. quota exceeded or private mode)
        }
    }

    function applyDomClasses(prefs) {
        const doc = document.documentElement;

        // Clean existing text scale classes
        Object.values(SCALE_MAP).forEach(item => {
            if (item.class) doc.classList.remove(item.class);
        });
        const currentScale = SCALE_MAP[String(prefs.textScale)];
        if (currentScale && currentScale.class) {
            doc.classList.add(currentScale.class);
        }

        doc.classList.toggle('a11y-high-contrast', !!prefs.highContrast);
        doc.classList.toggle('a11y-grayscale', !!prefs.grayscale);
        doc.classList.toggle('a11y-highlight-links', !!prefs.highlightLinks);
        doc.classList.toggle('a11y-readable-font', !!prefs.readableFont);
    }

    function countActivePrefs(prefs) {
        let count = 0;
        if (prefs.textScale !== 0) count++;
        if (prefs.highContrast) count++;
        if (prefs.grayscale) count++;
        if (prefs.highlightLinks) count++;
        if (prefs.readableFont) count++;
        return count;
    }

    function announce(message) {
        const announcer = document.getElementById('a11y-announcer');
        if (!announcer) return;
        announcer.textContent = '';
        setTimeout(() => {
            announcer.textContent = message;
        }, 50);
    }

    function initWidget() {
        const toggleBtn = document.getElementById('a11y-toggle-btn');
        const panel = document.getElementById('a11y-panel');
        const closeBtn = document.getElementById('a11y-close-btn');
        const badge = document.getElementById('a11y-badge');

        const decBtn = document.getElementById('a11y-dec-font');
        const incBtn = document.getElementById('a11y-inc-font');
        const resetFontBtn = document.getElementById('a11y-reset-font');
        const fontValue = document.getElementById('a11y-font-value');

        const contrastRow = document.getElementById('a11y-contrast-row');
        const contrastSwitch = document.getElementById('a11y-contrast-switch');

        const grayscaleRow = document.getElementById('a11y-grayscale-row');
        const grayscaleSwitch = document.getElementById('a11y-grayscale-switch');

        const linksRow = document.getElementById('a11y-links-row');
        const linksSwitch = document.getElementById('a11y-links-switch');

        const fontRow = document.getElementById('a11y-font-row');
        const fontSwitch = document.getElementById('a11y-font-switch');

        const resetAllBtn = document.getElementById('a11y-reset-all-btn');

        if (!toggleBtn || !panel) return;

        let prefs = loadPrefs();
        applyDomClasses(prefs);
        updateUiState();

        function updateUiState() {
            // Text scale UI
            if (fontValue) {
                const item = SCALE_MAP[String(prefs.textScale)] || SCALE_MAP['0'];
                fontValue.textContent = item.percent;
            }
            if (decBtn) decBtn.disabled = prefs.textScale <= MIN_SCALE;
            if (incBtn) incBtn.disabled = prefs.textScale >= MAX_SCALE;

            // Switches UI
            syncSwitch(contrastRow, contrastSwitch, prefs.highContrast);
            syncSwitch(grayscaleRow, grayscaleSwitch, prefs.grayscale);
            syncSwitch(linksRow, linksSwitch, prefs.highlightLinks);
            syncSwitch(fontRow, fontSwitch, prefs.readableFont);

            // Active badge
            const activeCount = countActivePrefs(prefs);
            if (badge) {
                badge.textContent = String(activeCount);
                badge.classList.toggle('visible', activeCount > 0);
            }
        }

        function syncSwitch(row, switchEl, isActive) {
            if (row) row.classList.toggle('active', !!isActive);
            if (switchEl) switchEl.setAttribute('aria-checked', isActive ? 'true' : 'false');
        }

        function openPanel() {
            panel.classList.add('active');
            panel.setAttribute('aria-hidden', 'false');
            toggleBtn.setAttribute('aria-expanded', 'true');
            if (closeBtn) closeBtn.focus();
        }

        function closePanel(returnFocus = true) {
            panel.classList.remove('active');
            panel.setAttribute('aria-hidden', 'true');
            toggleBtn.setAttribute('aria-expanded', 'false');
            if (returnFocus) toggleBtn.focus();
        }

        // Toggle button click
        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = panel.classList.contains('active');
            if (isOpen) {
                closePanel();
            } else {
                openPanel();
            }
        });

        // Close button click
        if (closeBtn) {
            closeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                closePanel();
            });
        }

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && panel.classList.contains('active')) {
                closePanel();
            }
        });

        // Close on clicking outside the widget
        document.addEventListener('click', (e) => {
            if (!panel.classList.contains('active')) return;
            if (!panel.contains(e.target) && !toggleBtn.contains(e.target)) {
                closePanel(false);
            }
        });

        // Text scale handlers
        if (decBtn) {
            decBtn.addEventListener('click', () => {
                if (prefs.textScale > MIN_SCALE) {
                    prefs.textScale--;
                    savePrefs(prefs);
                    applyDomClasses(prefs);
                    updateUiState();
                    announce('Text size decreased to ' + (SCALE_MAP[String(prefs.textScale)].percent));
                }
            });
        }

        if (incBtn) {
            incBtn.addEventListener('click', () => {
                if (prefs.textScale < MAX_SCALE) {
                    prefs.textScale++;
                    savePrefs(prefs);
                    applyDomClasses(prefs);
                    updateUiState();
                    announce('Text size increased to ' + (SCALE_MAP[String(prefs.textScale)].percent));
                }
            });
        }

        if (resetFontBtn) {
            resetFontBtn.addEventListener('click', () => {
                prefs.textScale = 0;
                savePrefs(prefs);
                applyDomClasses(prefs);
                updateUiState();
                announce('Text size reset to default 100%');
            });
        }

        // High contrast handler
        function toggleContrast() {
            prefs.highContrast = !prefs.highContrast;
            savePrefs(prefs);
            applyDomClasses(prefs);
            updateUiState();
            announce(prefs.highContrast ? 'High contrast enabled' : 'High contrast disabled');
        }
        if (contrastRow) {
            contrastRow.addEventListener('click', toggleContrast);
        }
        if (contrastSwitch) {
            contrastSwitch.addEventListener('keydown', (e) => {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleContrast();
                }
            });
        }

        // Grayscale handler
        function toggleGrayscale() {
            prefs.grayscale = !prefs.grayscale;
            savePrefs(prefs);
            applyDomClasses(prefs);
            updateUiState();
            announce(prefs.grayscale ? 'Grayscale enabled' : 'Grayscale disabled');
        }
        if (grayscaleRow) {
            grayscaleRow.addEventListener('click', toggleGrayscale);
        }
        if (grayscaleSwitch) {
            grayscaleSwitch.addEventListener('keydown', (e) => {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleGrayscale();
                }
            });
        }

        // Highlight links handler
        function toggleLinks() {
            prefs.highlightLinks = !prefs.highlightLinks;
            savePrefs(prefs);
            applyDomClasses(prefs);
            updateUiState();
            announce(prefs.highlightLinks ? 'Link highlights enabled' : 'Link highlights disabled');
        }
        if (linksRow) {
            linksRow.addEventListener('click', toggleLinks);
        }
        if (linksSwitch) {
            linksSwitch.addEventListener('keydown', (e) => {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleLinks();
                }
            });
        }

        // Readable font handler
        function toggleFont() {
            prefs.readableFont = !prefs.readableFont;
            savePrefs(prefs);
            applyDomClasses(prefs);
            updateUiState();
            announce(prefs.readableFont ? 'Readable font enabled' : 'Readable font disabled');
        }
        if (fontRow) {
            fontRow.addEventListener('click', toggleFont);
        }
        if (fontSwitch) {
            fontSwitch.addEventListener('keydown', (e) => {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleFont();
                }
            });
        }

        // Reset all settings handler
        if (resetAllBtn) {
            resetAllBtn.addEventListener('click', () => {
                prefs = Object.assign({}, defaultPrefs);
                if (storageAvailable) {
                    try {
                        localStorage.removeItem(STORAGE_KEY);
                    } catch (e) {}
                }
                inMemoryPrefs = Object.assign({}, defaultPrefs);
                applyDomClasses(prefs);
                updateUiState();
                announce('All accessibility settings have been reset to default');
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWidget);
    } else {
        initWidget();
    }
})();
