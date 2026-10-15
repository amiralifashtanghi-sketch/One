/**
 * EAFD Design Studio Live Preview Engine
 * Instant CSS Variable Manipulation & Real-Time WCAG 2.2 AA Contrast Analyzer
 */

document.addEventListener('DOMContentLoaded', function() {
    const tokenInputs = document.querySelectorAll('[data-token]');
    const contrastBadge = document.getElementById('contrast-badge');

    tokenInputs.forEach(input => {
        const handler = function() {
            const tokenKey = this.getAttribute('data-token');
            let val = this.value;

            // Ensure length tokens always include 'px' unit
            if (this.type === 'range' || tokenKey.includes('grid-size') || tokenKey.includes('radius')) {
                if (!val.endsWith('px') && !val.endsWith('rem') && !val.endsWith('%')) {
                    val = val + 'px';
                }
            }

            // Immediately set CSS variable on :root
            document.documentElement.style.setProperty(tokenKey, val);

            // Sync paired text/color/hidden or range display inputs
            const syncTargets = document.querySelectorAll(`[data-token="${tokenKey}"]`);
            syncTargets.forEach(target => {
                if (target !== this) {
                    if (target.tagName === 'SPAN' || target.tagName === 'LABEL') {
                        target.textContent = val;
                    } else if (target.type === 'hidden' || target.type === 'text') {
                        target.value = val;
                    } else if (target.type === 'range') {
                        target.value = parseInt(val, 10) || 0;
                    } else if (target.type === 'color') {
                        target.value = val;
                    }
                }
            });

            checkContrast();
        };

        input.addEventListener('input', handler);
        input.addEventListener('change', handler);
    });

    function getLuminance(hex) {
        if (!hex) return 0;
        hex = hex.replace('#', '').trim();
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }
        if (hex.length !== 6) return 0;

        let r = parseInt(hex.substring(0, 2), 16) / 255;
        let g = parseInt(hex.substring(2, 4), 16) / 255;
        let b = parseInt(hex.substring(4, 6), 16) / 255;

        r = (r <= 0.03928) ? r / 12.92 : Math.pow((r + 0.055) / 1.055, 2.4);
        g = (g <= 0.03928) ? g / 12.92 : Math.pow((g + 0.055) / 1.055, 2.4);
        b = (b <= 0.03928) ? b / 12.92 : Math.pow((b + 0.055) / 1.055, 2.4);

        return 0.2126 * r + 0.7152 * g + 0.0722 * b;
    }

    function checkContrast() {
        const bgInput = document.querySelector('[data-token="--eafd-color-bg"]');
        const textInput = document.querySelector('[data-token="--eafd-color-text"]');

        if (!bgInput || !textInput || !contrastBadge) return;

        const bgLuminance = getLuminance(bgInput.value);
        const textLuminance = getLuminance(textInput.value);

        let ratio = (bgLuminance > textLuminance)
            ? (bgLuminance + 0.05) / (textLuminance + 0.05)
            : (textLuminance + 0.05) / (bgLuminance + 0.05);

        ratio = Math.round(ratio * 100) / 100;

        if (ratio >= 4.5) {
            contrastBadge.style.background = '#14532d';
            contrastBadge.style.color = '#86efac';
            contrastBadge.textContent = '✓ تایید WCAG 2.2 AA (' + ratio + ':1)';
        } else {
            contrastBadge.style.background = '#7f1d1d';
            contrastBadge.style.color = '#fca5a5';
            contrastBadge.textContent = '✕ هشدار: کنتراست ضعیف (' + ratio + ':1 < 4.5:1)';
        }
    }

    checkContrast();
});
