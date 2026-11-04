<?php
if (defined('BUNIAFRIKA_A11Y_WIDGET_LOADED')) {
    return;
}
define('BUNIAFRIKA_A11Y_WIDGET_LOADED', true);
?>
<!-- Web Accessibility Widget -->
<div class="a11y-widget-root" id="a11y-widget-root">
    <!-- Floating Accessibility Button -->
    <button type="button" class="a11y-toggle-btn" id="a11y-toggle-btn" aria-haspopup="dialog" aria-expanded="false" aria-controls="a11y-panel" aria-label="Open accessibility settings" title="Open accessibility settings">
        <svg class="a11y-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/>
        </svg>
        <span class="a11y-active-badge" id="a11y-badge" aria-hidden="true">0</span>
    </button>

    <!-- Accessibility Settings Panel -->
    <aside id="a11y-panel" class="a11y-panel" role="region" aria-label="Accessibility Settings" aria-hidden="true">
        <div class="a11y-panel-header">
            <h2 class="a11y-panel-title">
                <svg class="a11y-header-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/>
                </svg>
                Accessibility Settings
            </h2>
            <button type="button" class="a11y-close-btn" id="a11y-close-btn" aria-label="Close accessibility settings" title="Close accessibility settings">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
        </div>

        <div class="a11y-panel-body">
            <!-- Text Resizing -->
            <div class="a11y-group">
                <span class="a11y-group-label" id="a11y-text-size-label">Text Size</span>
                <div class="a11y-text-resizer" role="group" aria-labelledby="a11y-text-size-label">
                    <button type="button" class="a11y-scale-btn" id="a11y-dec-font" aria-label="Decrease text size" title="Decrease text size">
                        <span aria-hidden="true">A-</span>
                    </button>
                    <span class="a11y-scale-display" id="a11y-font-value" aria-live="polite">100%</span>
                    <button type="button" class="a11y-scale-btn" id="a11y-inc-font" aria-label="Increase text size" title="Increase text size">
                        <span aria-hidden="true">A+</span>
                    </button>
                    <button type="button" class="a11y-scale-btn a11y-scale-btn-reset" id="a11y-reset-font" aria-label="Reset text size to default" title="Reset text size">
                        Reset
                    </button>
                </div>
            </div>

            <!-- Visual Controls -->
            <div class="a11y-group">
                <span class="a11y-group-label">Display &amp; Readability</span>

                <!-- High Contrast -->
                <div class="a11y-toggle-row" id="a11y-contrast-row">
                    <div class="a11y-toggle-meta">
                        <svg class="a11y-feature-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8v16z"/>
                        </svg>
                        <div class="a11y-toggle-text">
                            <span class="a11y-feature-title" id="a11y-contrast-title">High Contrast</span>
                            <span class="a11y-feature-desc">Enhances text &amp; borders</span>
                        </div>
                    </div>
                    <button type="button" class="a11y-switch" id="a11y-contrast-switch" role="switch" aria-checked="false" aria-labelledby="a11y-contrast-title">
                        <span class="a11y-switch-thumb" aria-hidden="true"></span>
                    </button>
                </div>

                <!-- Grayscale -->
                <div class="a11y-toggle-row" id="a11y-grayscale-row">
                    <div class="a11y-toggle-meta">
                        <svg class="a11y-feature-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.38-.61-.38-.99 0-.83.67-1.5 1.5-1.5H16c2.76 0 5-2.24 5-5 0-4.42-4.03-8-9-8zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 9 6.5 9 8 9.67 8 10.5 7.33 12 6.5 12zm3-4C8.67 8 8 7.33 8 6.5S8.67 5 9.5 5s1.5.67 1.5 1.5S10.33 8 9.5 8zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 5 14.5 5s1.5.67 1.5 1.5S15.33 8 14.5 8zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 9 17.5 9s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                        </svg>
                        <div class="a11y-toggle-text">
                            <span class="a11y-feature-title" id="a11y-grayscale-title">Grayscale</span>
                            <span class="a11y-feature-desc">Removes color saturation</span>
                        </div>
                    </div>
                    <button type="button" class="a11y-switch" id="a11y-grayscale-switch" role="switch" aria-checked="false" aria-labelledby="a11y-grayscale-title">
                        <span class="a11y-switch-thumb" aria-hidden="true"></span>
                    </button>
                </div>

                <!-- Highlight Links -->
                <div class="a11y-toggle-row" id="a11y-links-row">
                    <div class="a11y-toggle-meta">
                        <svg class="a11y-feature-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/>
                        </svg>
                        <div class="a11y-toggle-text">
                            <span class="a11y-feature-title" id="a11y-links-title">Highlight Links</span>
                            <span class="a11y-feature-desc">Adds underlines &amp; outlines</span>
                        </div>
                    </div>
                    <button type="button" class="a11y-switch" id="a11y-links-switch" role="switch" aria-checked="false" aria-labelledby="a11y-links-title">
                        <span class="a11y-switch-thumb" aria-hidden="true"></span>
                    </button>
                </div>

                <!-- Readable Font -->
                <div class="a11y-toggle-row" id="a11y-font-row">
                    <div class="a11y-toggle-meta">
                        <svg class="a11y-feature-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M9.93 13.5h4.14L12 7.98zM20 2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-4.05 16.5l-1.14-3H9.17l-1.12 3H5.96l5.11-13h1.86l5.11 13h-2.09z"/>
                        </svg>
                        <div class="a11y-toggle-text">
                            <span class="a11y-feature-title" id="a11y-font-title">Readable Font</span>
                            <span class="a11y-feature-desc">Clean system sans-serif font</span>
                        </div>
                    </div>
                    <button type="button" class="a11y-switch" id="a11y-font-switch" role="switch" aria-checked="false" aria-labelledby="a11y-font-title">
                        <span class="a11y-switch-thumb" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="a11y-panel-footer">
            <button type="button" class="a11y-reset-all-btn" id="a11y-reset-all-btn">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.95 20 14.54 20 13c0-4.42-3.58-8-8-8zm-6 8c0-1.01.25-1.97.7-2.8L5.24 8.74C4.46 10.05 4 11.46 4 13c0 4.42 3.58 8 8 8v4l5-5-5-5v4c-3.31 0-6-2.69-6-6z"/>
                </svg>
                Reset all settings
            </button>
        </div>

        <!-- Live announcement region for screen readers -->
        <div id="a11y-announcer" class="a11y-sr-only" aria-live="polite" aria-atomic="true"></div>
    </aside>
</div>
