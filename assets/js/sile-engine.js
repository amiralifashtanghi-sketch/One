/**
 * Smart Image Loading Engine - Core JS
 */
class ImageSchedulerEngine {
    constructor() {
        this.settings = window.sileVars ? window.sileVars.settings : {};
        this.queue = [];
        this.activeDownloads = 0;
        this.maxConcurrent = parseInt(this.settings.concurrent_downloads) || 4;
        this.observer = null;

        this.init();
    }

    static getInstance() {
        if (!ImageSchedulerEngine.instance) {
            ImageSchedulerEngine.instance = new ImageSchedulerEngine();
        }
        return ImageSchedulerEngine.instance;
    }

    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.start());
        } else {
            this.start();
        }

        if (this.settings.enable_idle_preload === 'yes' && 'requestIdleCallback' in window) {
            window.requestIdleCallback(() => this.idlePreload());
        }
    }

    idlePreload() {
        const remaining = document.querySelectorAll('.sile-image:not(.sile-processed), .sile-bg-image:not(.sile-processed)');
        remaining.forEach(el => {
            if (this.queue.length < 5) {
                this.loadImage(el);
            }
        });
    }

    start() {
        this.setupObserver();
        this.findImages();

        if (window.sileVars && window.sileVars.debug) {
            this.initDebug();
        }
    }

    initDebug() {
        this.debugEl = document.createElement('div');
        this.debugEl.id = 'sile-debug-overlay';
        document.body.appendChild(this.debugEl);
        this.updateDebug();
        setInterval(() => this.updateDebug(), 1000);
    }

    updateDebug() {
        if (!this.debugEl) return;

        const loaded = document.querySelectorAll('.sile-loaded').length;
        const total = document.querySelectorAll('.sile-image, .sile-bg-image').length;
        const memory = window.performance && window.performance.memory ?
                       Math.round(window.performance.memory.usedJSHeapSize / (1024 * 1024)) + 'MB' : 'N/A';

        this.debugEl.innerHTML = `
            <b>SILE DEBUG MODE</b><br>
            Total Images: <b>${total}</b><br>
            Loaded: <b>${loaded}</b><br>
            In Queue: <b>${this.queue.length}</b><br>
            Active Downloads: <b>${this.activeDownloads}</b> / ${this.maxConcurrent}<br>
            Memory: <b>${memory}</b><br>
            Observer: <b>Active</b>
        `;
    }

    setupObserver() {
        const margin = parseInt(this.settings.intersection_margin) || 300;
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    // For standard lazy load, we unobserve after loading starts or finishes.
                    // But if we want to abort when leaving viewport, we might need a different strategy.
                    // However, requirement says "Wait until image becomes close to viewport",
                    // and "Abort if leaves before necessary".
                } else {
                    // Check if it was loading and we should abort
                    if (entry.target._sile_controller) {
                        entry.target._sile_controller.abort();
                        // Re-observe so it can load again when it comes back
                        this.observer.observe(entry.target);
                    }
                }
            });
        }, {
            rootMargin: `${margin}px 0px`,
            threshold: 0
        });
    }

    findImages() {
        const images = document.querySelectorAll('.sile-image:not(.sile-processed)');
        images.forEach(img => {
            this.prepareImage(img);
        });

        const bgImages = document.querySelectorAll('.sile-bg-image:not(.sile-processed)');
        bgImages.forEach(el => {
            this.prepareBgImage(el);
        });
    }

    prepareBgImage(el) {
        el.classList.add('sile-processed');
        this.observer.observe(el);
    }

    prepareImage(img) {
        img.classList.add('sile-processed');

        // Wrap in container for skeleton if enabled
        if (this.settings.skeleton === 'yes') {
            this.createSkeleton(img);
        }

        // Check if it's high priority (LCP candidate from server or logo)
        if (img.dataset.silePriority === 'P1') {
            this.loadImage(img);
        } else {
            this.observer.observe(img);
        }
    }

    createSkeleton(img) {
        const wrapper = document.createElement('div');
        wrapper.className = 'sile-container';

        // Try to match image display/dimensions
        const style = window.getComputedStyle(img);
        wrapper.style.display = style.display === 'inline' ? 'inline-block' : style.display;
        wrapper.style.width = style.width;
        wrapper.style.height = style.height;
        wrapper.style.aspectRatio = style.aspectRatio;

        const skeleton = document.createElement('div');
        skeleton.className = 'sile-skeleton';

        img.parentNode.insertBefore(wrapper, img);
        wrapper.appendChild(skeleton);
        wrapper.appendChild(img);
    }

    loadImage(el) {
        let src, srcset, isBg = false;

        if (el.classList.contains('sile-bg-image')) {
            isBg = true;
            src = el.dataset.sileBgUrl;
        } else {
            src = el.dataset.sileSrc;
            srcset = el.dataset.sileSrcset;
        }

        if (!src) return;

        const isP1 = el.dataset.silePriority === 'P1';

        if (this.settings.enable_queue === 'yes' && !isP1) {
            this.addToQueue(el, src, srcset, isBg);
        } else {
            // Bypass queue for P1 or if queue is disabled
            this.processDownload(el, src, srcset, isBg);
        }
    }

    addToQueue(el, src, srcset, isBg) {
        this.queue.push({ el, src, srcset, isBg });
        this.processQueue();
    }

    processQueue() {
        while (this.activeDownloads < this.maxConcurrent && this.queue.length > 0) {
            const item = this.queue.shift();
            this.processDownload(item.el, item.src, item.srcset, item.isBg);
        }
    }

    async processDownload(el, src, srcset, isBg = false) {
        this.activeDownloads++;

        const controller = new AbortController();
        const signal = controller.signal;

        // Store controller to allow abortion if element leaves viewport
        el._sile_controller = controller;

        try {
            const tempImg = new Image();
            if (srcset) tempImg.srcset = srcset;
            tempImg.src = src;

            // Use AbortSignal if supported (not directly by decode() but we can wrap it)
            const decodePromise = tempImg.decode();
            const abortPromise = new Promise((_, reject) => {
                signal.addEventListener('abort', () => reject(new Error('SILE_ABORTED')));
            });

            await Promise.race([decodePromise, abortPromise]);

            if (isBg) {
                el.style.backgroundImage = `url('${src}')`;
                el.style.transition = `opacity ${this.settings.fade_duration}ms ease`;
                el.classList.add('sile-loaded');
                this.observer.unobserve(el);
            } else {
                if (srcset) el.srcset = srcset;
                el.src = src;
                this.revealImage(el);
                this.observer.unobserve(el);
            }
        } catch (error) {
            if (error.message !== 'SILE_ABORTED') {
                console.error('SILE: Image failed to load/decode', src, error);
            }
        } finally {
            delete el._sile_controller;
            this.activeDownloads--;
            this.processQueue();
        }
    }

    revealImage(img) {
        const container = img.closest('.sile-container');
        const skeleton = container ? container.querySelector('.sile-skeleton') : null;

        img.style.transition = `opacity ${this.settings.fade_duration}ms ease`;
        img.style.opacity = '1';

        if (skeleton) {
            skeleton.style.transition = `opacity ${this.settings.fade_duration}ms ease`;
            skeleton.style.opacity = '0';
            setTimeout(() => skeleton.remove(), parseInt(this.settings.fade_duration));
        }

        img.classList.add('sile-loaded');
    }
}

// Global initialization
window.SILE = ImageSchedulerEngine.getInstance();
