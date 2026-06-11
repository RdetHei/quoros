import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Theme logic
if (localStorage.getItem('color-theme') === 'dark' || !('color-theme' in localStorage) || window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark')
}

// Global Cropper Logic (Lazy Loaded)
let currentCropper = null;
let currentPreviewId = null;
let currentInput = null;
let currentCropOptions = {};

window.initCropper = async function(input, previewId, options) {
    if (input.files && input.files[0]) {
        // Dynamic import to reduce initial bundle size
        const { default: Cropper } = await import('cropperjs');
        
        const reader = new FileReader();
        reader.onload = function (e) {
            const modal = document.getElementById('cropping-modal');
            const image = document.getElementById('cropping-image');
            
            if (!modal || !image) {
                console.error('Cropping modal elements not found');
                return;
            }

            if (currentCropper) {
                currentCropper.destroy();
                currentCropper = null;
            }
            
            currentInput = input;
            currentPreviewId = previewId;
            currentCropOptions = options || {};
            
            image.onload = () => {
                currentCropper = new Cropper(image, {
                    aspectRatio: currentCropOptions.aspectRatio || 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            };
            
            image.src = e.target.result;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };
        reader.readAsDataURL(input.files[0]);
    }
};

window.saveCrop = async function() {
    if (!currentCropper) return;
    
    let canvasOptions = {
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    };

    if (currentCropOptions.width) canvasOptions.width = currentCropOptions.width;
    if (currentCropOptions.height) canvasOptions.height = currentCropOptions.height;
    
    const canvas = currentCropper.getCroppedCanvas(canvasOptions);
    
    canvas.toBlob((blob) => {
        const preview = document.getElementById(currentPreviewId);
        if (preview) {
            preview.src = URL.createObjectURL(blob);
            preview.classList.remove('hidden');
            
            const placeholderId = currentCropOptions.placeholderId || 'profile-photo-placeholder';
            const placeholder = document.getElementById(placeholderId);
            if (placeholder) placeholder.classList.add('hidden');
            
            if (!currentCropOptions.placeholderId && document.getElementById('cover-placeholder')) {
                document.getElementById('cover-placeholder').classList.add('hidden');
            }
        }
        
        const file = new File([blob], 'cropped_image.jpg', { type: 'image/jpeg' });
        const container = new DataTransfer();
        container.items.add(file);
        currentInput.files = container.files;
        
        if (currentCropOptions.onSave) {
            currentCropOptions.onSave();
        }
        
        window.closeCropModal();
    }, 'image/jpeg', 0.9);
};

window.closeCropModal = function() {
    const modal = document.getElementById('cropping-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    if (currentCropper) {
        currentCropper.destroy();
        currentCropper = null;
    }
};

// Start Alpine
Alpine.start();

// Lazy Load landingHero only if needed
if (document.querySelector('[x-data*="landingHero"]')) {
    window.landingHero = function(initialNovels) {
        return {
            activeSlide: 0,
            novels: initialNovels,
            paused: false,
            timer: null,
            get current() { return this.novels[this.activeSlide] || {}; },
            get slideCount() { return this.novels.length; },
            init() {
                if (this.slideCount > 1) this.startTimer();
            },
            goTo(index) {
                this.activeSlide = index;
                this.resetTimer();
            },
            next() {
                this.activeSlide = (this.activeSlide + 1) % this.slideCount;
            },
            startTimer() {
                this.timer = setInterval(() => {
                    if (!this.paused) this.next();
                }, 6000);
            },
            resetTimer() {
                clearInterval(this.timer);
                if (this.slideCount > 1) this.startTimer();
            },
        };
    };
}

// Live Search & Novel Hover logic
document.addEventListener('DOMContentLoaded', function() {
    // Live Search
    const searchInputs = document.querySelectorAll('.live-search-input');
    searchInputs.forEach(input => {
        const componentId = input.id.replace('-input', '');
        const dropdown = document.getElementById(`${componentId}-dropdown`);
        const resultsContainer = document.getElementById(`${componentId}-results`);
        const loadingIndicator = document.getElementById(`${componentId}-loading`);
        const footer = document.getElementById(`${componentId}-footer`);
        const emptyState = document.getElementById(`${componentId}-empty`);
        let debounceTimer;

        input.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(debounceTimer);

            if (query.length < 2) {
                if (dropdown) dropdown.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(async () => {
                if (dropdown) dropdown.style.display = 'block';
                if (loadingIndicator) loadingIndicator.classList.remove('hidden');
                if (resultsContainer) resultsContainer.innerHTML = '';
                if (footer) footer.classList.add('hidden');
                if (emptyState) emptyState.classList.add('hidden');

                try {
                    const response = await fetch(`/api/live-search?q=${encodeURIComponent(query)}`);
                    const results = await response.json();

                    if (loadingIndicator) loadingIndicator.classList.add('hidden');

                    if (results.length > 0) {
                        results.forEach(novel => {
                            const item = document.createElement('a');
                            item.href = novel.url;
                            item.className = 'flex items-center gap-3 p-3 hover:bg-slate-800/50 transition-colors group';
                            item.innerHTML = `
                                <div class="w-10 h-14 shrink-0 rounded overflow-hidden bg-slate-800 ring-1 ring-slate-700">
                                    <img src="${novel.cover_image || '/error.png'}" 
                                         alt="${novel.title} cover"
                                         width="40" height="56"
                                         class="w-full h-full object-cover" 
                                         loading="lazy">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-200 group-hover:text-white truncate">${novel.title}</h4>
                                    <p class="text-[10px] text-slate-500 mt-0.5">${novel.author}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[9px] px-1.5 py-0.5 bg-slate-800 text-slate-400 rounded uppercase tracking-wider font-bold">${novel.type}</span>
                                        <span class="text-[9px] text-amber-500 font-bold flex items-center gap-0.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            ${novel.rating_avg}
                                        </span>
                                    </div>
                                </div>
                            `;
                            if (resultsContainer) resultsContainer.appendChild(item);
                        });
                        if (footer) footer.classList.remove('hidden');
                    } else {
                        if (emptyState) emptyState.classList.remove('hidden');
                    }
                } catch (e) {
                    console.error('Search error:', e);
                    if (loadingIndicator) loadingIndicator.classList.add('hidden');
                }
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (dropdown && !input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    });

    // Novel Hover
    document.addEventListener('mouseover', function(e) {
        const target = e.target.closest('[data-novel-id]');
        if (target) {
            const rect = target.getBoundingClientRect();
            window.dispatchEvent(new CustomEvent('novel-hover-show', {
                detail: {
                    id: target.dataset.novelId,
                    x: rect.right,
                    y: rect.top + (rect.height / 2)
                }
            }));
        }
    });

    document.addEventListener('mouseout', function(e) {
        const target = e.target.closest('[data-novel-id]');
        if (target) {
            window.dispatchEvent(new CustomEvent('novel-hover-hide'));
        }
    });
});
