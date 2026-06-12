import 'cropperjs/dist/cropper.css';

let currentCropper = null;
let currentPreviewId = null;
let currentInput = null;
let currentCropOptions = {};

export async function initCropper(input, previewId, options) {
    if (input.files && input.files[0]) {
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
}

export async function saveCrop() {
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
        
        closeCropModal();
    }, 'image/jpeg', 0.9);
}

export function closeCropModal() {
    const modal = document.getElementById('cropping-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    if (currentCropper) {
        currentCropper.destroy();
        currentCropper = null;
    }
}
