document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img.wp-skeleton-img[data-wp-skeleton="true"]');

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    loadImage(img);
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '200px 0px', // Start loading 200px before they enter viewport
            threshold: 0.01
        });

        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for older browsers
        images.forEach(img => loadImage(img));
    }

    function loadImage(img) {
        const src = img.getAttribute('data-src');
        const srcset = img.getAttribute('data-srcset');
        const sizes = img.getAttribute('data-sizes');

        if (srcset) {
            img.srcset = srcset;
        }
        if (sizes) {
            img.sizes = sizes;
        }
        if (src) {
            img.src = src;
        }

        img.onload = function() {
            img.classList.add('loaded');
            // Remove skeleton data to clean up DOM
            img.removeAttribute('data-wp-skeleton');
        };

        // If image is already in cache
        if (img.complete && img.naturalWidth > 0) {
            img.classList.add('loaded');
        }
    }
});
