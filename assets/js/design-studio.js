/**
 * EAFD Design Studio Live Preview Engine
 * Instant CSS Variable Manipulation & Real-Time WCAG 2.2 AA Contrast Analyzer
 */

document.addEventListener('DOMContentLoaded', function() {
    const colorInputs = document.querySelectorAll('.token-color-input');
    const textInputs = document.querySelectorAll('.token-text-input');
    const contrastBadge = document.getElementById('contrast-badge');
    const contrastValue = document.getElementById('contrast-value');

    // Bind inputs to CSS variables in real-time
    colorInputs.forEach(input => {
        input.addEventListener('input', function() {
            const tokenKey = this.getAttribute('data-token');
            document.documentElement.style.setProperty(tokenKey, this.value);

            // Sync text input next to color picker
            const textInput = document.querySelector(`input[data-token="${tokenKey}"].token-text-input`);
            if (textInput) {
                textInput.value = this.value;
            }

            checkContrast();
        });
    });

    textInputs.forEach(input => {
        input.addEventListener('input', function() {
            const tokenKey = this.getAttribute('data-token');
            document.documentElement.style.setProperty(tokenKey, this.value);

            const colorInput = document.querySelector(`input[data-token="${tokenKey}"].token-color-input`);
            if (colorInput) {
                colorInput.value = this.value;
            }

            checkContrast();
        });
    });

    function getLuminance(hex) {
        hex = hex.replace('#', '');
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }

        let r = parseInt(hex.substring(0, 2), 16) / 255;
        let g = parseInt(hex.substring(2, 4), 16) / 255;
        let b = parseInt(hex.substring(4, 6), 16) / 255;

        r = (r <= 0.03928) ? r / 12.92 : Math.pow((r + 0.055) / 1.055, 2.4);
        g = (g <= 0.03928) ? g / 12.92 : Math.pow((g + 0.055) / 1.055, 2.4);
        b = (b <= 0.03928) ? b / 12.92 : Math.pow((b + 0.055) / 1.055, 2.4);

        return 0.2126 * r + 0.7152 * g + 0.0722 * b;
    }

    function checkContrast() {
        const bgInput = document.querySelector('input[data-token="--eafd-color-bg"]');
        const textInput = document.querySelector('input[data-token="--eafd-color-text"]');

        if (!bgInput || !textInput || !contrastBadge || !contrastValue) return;

        const bgLuminance = getLuminance(bgInput.value);
        const textLuminance = getLuminance(textInput.value);

        let ratio = (bgLuminance > textLuminance)
            ? (bgLuminance + 0.05) / (textLuminance + 0.05)
            : (textLuminance + 0.05) / (bgLuminance + 0.05);

        ratio = Math.round(ratio * 100) / 100;
        contrastValue.textContent = ratio + ':1';

        if (ratio >= 4.5) {
            contrastBadge.className = 'badge badge-success';
            contrastBadge.textContent = '✓ تایید WCAG 2.2 AA (' + ratio + ':1)';
        } else {
            contrastBadge.className = 'badge badge-danger';
            contrastBadge.textContent = '✕ هشدار: کنتراست ضعیف (' + ratio + ':1 < 4.5:1)';
        }
    }

    checkContrast();
});
