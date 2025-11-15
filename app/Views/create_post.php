<?php
$title = 'Create Post | AuthBoard';
ob_start();
?>

<div class="max-w-2xl mx-auto">
    <div class="bg-slate-800 border border-slate-600 rounded-2xl shadow-2xl p-10">
        <h2 class="text-3xl font-bold mb-8 bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">✨ Create a New Post</h2>

        <form method="POST" action="/post/create" enctype="multipart/form-data" class="space-y-6">
            <!-- Content Textarea -->
            <div>
                <label for="content" class="block text-sm font-semibold text-slate-300 mb-3">What's on your mind?</label>
                <textarea 
                    id="content" 
                    name="content" 
                    required 
                    rows="6"
                    placeholder="Share your thoughts, ideas, or updates..." 
                    class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                ></textarea>
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-semibold text-slate-300 mb-3">📸 Add an image (optional)</label>
                <div class="relative">
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/*"
                        class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer file:bg-blue-600 file:text-white file:border-0 file:rounded-md file:px-4 file:py-2 file:cursor-pointer file:hover:bg-blue-700"
                    />
                </div>
            </div>

            <!-- Image Preview -->
            <div id="imagePreviewContainer" class="hidden">
                <label class="block text-sm font-semibold text-slate-300 mb-3">📷 Image Preview</label>
                <div class="bg-slate-700 border border-slate-600 rounded-lg p-4 overflow-auto max-h-96">
                    <img id="imagePreview" src="" alt="Image preview" class="w-full rounded-lg object-cover">
                </div>
                <button 
                    type="button" 
                    id="removeImageBtn"
                    class="mt-3 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors"
                >
                    Remove Image
                </button>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full mt-8 px-4 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105"
            >
                Post Now 🚀
            </button>
        </form>

        <!-- Back Link -->
        <div class="mt-6 text-center">
            <a href="/dashboard" class="text-slate-400 hover:text-cyan-400 transition-colors">← Back to Dashboard</a>
        </div>
    </div>
</div>

<script>
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const removeImageBtn = document.getElementById('removeImageBtn');

    // Show preview when image is selected
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(event) {
                imagePreview.src = event.target.result;
                imagePreviewContainer.classList.remove('hidden');
            };
            
            reader.readAsDataURL(file);
        }
    });

    // Remove image functionality
    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        imagePreviewContainer.classList.add('hidden');
        imagePreview.src = '';
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
