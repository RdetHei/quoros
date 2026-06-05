<!-- Cropping Modal -->
<div id="cropping-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeCropModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between shrink-0">
            <div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-wider">Crop Image</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-0.5">Adjust your cover image for best fit</p>
            </div>
            <button onclick="closeCropModal()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Image Container -->
        <div class="bg-slate-50 dark:bg-slate-950/50 flex-grow flex items-center justify-center min-h-[400px] relative p-4">
            <div class="w-full h-full flex items-center justify-center overflow-hidden">
                <img id="cropping-image" src="" class="block max-w-full max-h-[60vh]">
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="px-8 py-6 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-end gap-4 shrink-0">
            <button onclick="closeCropModal()" class="px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">
                Cancel
            </button>
            <button onclick="saveCrop()" class="px-8 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20">
                Apply Crop
            </button>
        </div>
    </div>
</div>

<style>
    /* Ensure cropper doesn't get squashed */
    .cropper-container {
        max-width: 100% !important;
    }
    .cropper-view-box,
    .cropper-face {
        border-radius: 1rem;
    }
</style>
