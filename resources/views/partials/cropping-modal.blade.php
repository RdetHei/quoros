<div id="cropping-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeCropModal()"></div>

    <div class="relative w-full max-w-2xl bg-neutral-900 rounded-xl shadow-2xl border border-neutral-800 overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-5 border-b border-neutral-800 flex items-center justify-between shrink-0">
            <div>
                <h3 class="text-lg font-semibold text-white">Crop Image</h3>
                <p class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider mt-0.5">Adjust your image for best fit</p>
            </div>
            <button onclick="closeCropModal()" class="p-2 text-neutral-500 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 bg-black overflow-hidden flex-grow flex items-center justify-center min-h-[300px]">
            <div class="max-w-full max-h-[50vh] flex items-center justify-center">
                <img id="cropping-image" src="" class="block max-w-full max-h-[50vh]">
            </div>
        </div>

        <div class="px-6 py-5 border-t border-neutral-800 flex items-center justify-end gap-3 shrink-0">
            <button onclick="closeCropModal()" class="px-5 py-2.5 text-xs font-medium uppercase tracking-wider text-neutral-400 hover:text-white transition-colors">
                Cancel
            </button>
            <button onclick="saveCrop()" class="px-6 py-2.5 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all">
                Apply Crop
            </button>
        </div>
    </div>
</div>

<style>
    .cropper-container { max-width: 100% !important; }
    .cropper-view-box, .cropper-face { border-radius: 0.5rem; }
</style>
