<div
    x-data="{ open: false, violationId: null }"
    x-show="open"
    x-cloak
    style="display: none;"
    @open-appeal.window="violationId = $event.detail; open = true;"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
>
    <!-- Dark overlay: click to close -->
    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

    <!-- White card -->
    <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">

        <!-- Header -->
        <div class="bg-orange-800 px-6 py-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold">Appeal Violation</h3>
                    <p class="text-orange-200 text-sm mt-1">Submit your request for SAO review.</p>
                </div>
                <button type="button" @click="open = false" class="text-orange-200 hover:text-white text-2xl" aria-label="Close">
                    &times;
                </button>
            </div>
        </div>

        <!-- Body -->
        <form method="POST" :action="`/student/violations/${violationId}/appeal`" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for Appeal</label>
                <textarea name="reason" rows="5" required minlength="20" maxlength="2000"
                    class="w-full rounded-xl border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                    placeholder="Explain why you believe this violation should be reviewed..."></textarea>
                <p class="text-xs text-gray-400 mt-1">Please provide a clear and factual explanation.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Supporting Evidence
                    <span class="font-normal text-gray-400">(Optional)</span>
                </label>
                <input type="file" onchange="handleImageUpload(event)" id="evidence_image" name="evidence_image" accept=".jpg,.jpeg,.png"
                    class="block w-full text-sm text-gray-600 border border-gray-300 rounded-xl p-2">
                <p class="text-xs text-gray-400 mt-1">JPG or PNG, maximum 2MB.</p>
            </div>

            <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 mb-6">
                <p class="text-sm text-orange-900">
                    <strong>Before submitting:</strong>
                    Please provide accurate information and any supporting evidence relevant to your appeal.
                    The Student Affairs Office will review your submission.
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" @click="open = false"
                    class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-orange-800 text-white font-semibold hover:bg-orange-900">
                    Submit Appeal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    async function handleImageUpload(event) {
            const imageFile = event.target.files[0];
            if (!imageFile) return;

            // Show a "Compressing..." status if you want
            console.log('originalFile instanceof Blob', imageFile instanceof Blob);
            console.log(`originalFile size ${imageFile.size / 1024 / 1024} MB`);

            const submitBtn = document.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerText;
            submitBtn.disabled = true;
            submitBtn.innerText = 'Optimizing Image...';

            const options = {
                maxSizeMB: 0.8,          // Max size is 800KB (Great for evidence)
                maxWidthOrHeight: 1280, // Resizes large 4K photos to standard HD
                useWebWorker: true,
            }

            try {
                const compressedFile = await imageCompression(imageFile, options);
                console.log(`compressedFile size ${compressedFile.size / 1024 / 1024} MB`);

                // Now, we must put this compressed file back into the FileList
                // We use a DataTransfer object to simulate a file selection
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(new File([compressedFile], imageFile.name, {
                    type: imageFile.type,
                }));

                event.target.files = dataTransfer.files;

                alert('Image optimized for faster upload!');
            } catch (error) {
                console.log(error);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = originalBtnText;
            }
        }
</script>