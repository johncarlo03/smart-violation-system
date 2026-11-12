<div
    x-data="{ open: false, violationId: null }"
    x-show="open"
    x-cloak
    style="display: none;"
    @open-appeal.window="violationId = $event.detail; open = true;"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
>
    <!-- Dark overlay with smooth fade transition -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm" 
        @click="open = false"
    ></div>

    <!-- Modal Card with scale transition -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden my-auto max-h-[90vh] flex flex-col z-10"
    >

        <!-- Header -->
        <div class="bg-orange-800 px-5 py-4 text-white shrink-0">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-base sm:text-lg font-bold">Appeal Violation</h3>
                    <p class="text-orange-200 text-xs sm:text-sm mt-0.5">Submit your request for SAO review.</p>
                </div>
                
                <!-- Modernized Close Button -->
                <button 
                    type="button" 
                    @click="open = false" 
                    class="shrink-0 p-1.5 rounded-full text-orange-200 hover:text-white hover:bg-orange-700/60 focus:outline-none focus:ring-2 focus:ring-orange-400 transition"
                    aria-label="Close modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Body Form -->
        <form method="POST" :action="`/student/violations/${violationId}/appeal`" enctype="multipart/form-data" class="p-5 sm:p-6 overflow-y-auto space-y-4">
            @csrf

            <div>
                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5">Reason for Appeal</label>
                <textarea name="reason" rows="4" required minlength="20" maxlength="2000"
                    class="w-full rounded-xl border-gray-300 text-xs sm:text-sm focus:border-orange-500 focus:ring-orange-500 transition shadow-sm"
                    placeholder="Explain why you believe this violation should be reviewed..."></textarea>
                <p class="text-[11px] text-gray-500 mt-1">Please provide a clear and factual explanation. (Minimum of 20 characters)</p>
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5">
                    Supporting Evidence
                    <span class="font-normal text-gray-400">(Optional)</span>
                </label>
                <input type="file" onchange="handleImageUpload(event)" id="evidence_image" name="evidence_image" accept=".jpg,.jpeg,.png"
                    class="block w-full text-xs sm:text-sm text-gray-600 border border-gray-300 rounded-xl p-2 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-800 hover:file:bg-orange-100 transition cursor-pointer">
                <p id="upload-status" class="text-[11px] text-gray-400 mt-1">JPG or PNG, maximum 2MB.</p>
            </div>

            <div class="bg-orange-50/80 border border-orange-100 rounded-xl p-3.5">
                <p class="text-xs text-orange-950 leading-relaxed">
                    <strong class="font-bold">Before submitting:</strong>
                    Please provide accurate information and any supporting evidence relevant to your appeal.
                    The Student Affairs Office will review your submission.
                </p>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" @click="open = false"
                    class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 text-xs sm:text-sm font-semibold hover:bg-gray-200 transition focus:outline-none">
                    Cancel
                </button>
                <button type="submit" id="submit-appeal-btn"
                    class="px-5 py-2 rounded-xl bg-orange-800 text-white text-xs sm:text-sm font-semibold hover:bg-orange-900 transition focus:outline-none active:scale-[0.99] shadow-sm">
                    Submit Appeal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    window.openAppealModal = function(violationId) {
        window.dispatchEvent(new CustomEvent('open-appeal', { detail: violationId }));
    };

    window.handleImageUpload = async function(event) {
        const imageFile = event.target.files[0];
        if (!imageFile) return;

        const submitBtn = document.getElementById('submit-appeal-btn');
        const statusText = document.getElementById('upload-status');
        const originalBtnText = submitBtn.innerText;
        
        submitBtn.disabled = true;
        submitBtn.innerText = 'Optimizing Image...';
        if (statusText) {
            statusText.innerText = 'Compressing image for upload...';
            statusText.classList.replace('text-gray-400', 'text-orange-700');
        }

        const options = {
            maxSizeMB: 0.8,
            maxWidthOrHeight: 1280,
            useWebWorker: true,
        };

        try {
            const compressedFile = await imageCompression(imageFile, options);

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(new File([compressedFile], imageFile.name, {
                type: imageFile.type,
            }));

            event.target.files = dataTransfer.files;

            if (statusText) {
                statusText.innerText = `Image compressed successfully (${(compressedFile.size / 1024).toFixed(0)} KB)!`;
                statusText.classList.replace('text-orange-700', 'text-emerald-600');
            }
        } catch (error) {
            console.error(error);
            if (statusText) {
                statusText.innerText = 'Error compressing image. Original file kept.';
                statusText.classList.replace('text-orange-700', 'text-rose-600');
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = originalBtnText;
        }
    };
</script>