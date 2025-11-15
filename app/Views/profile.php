<?php
$title = 'Profile | AuthBoard';
ob_start();
?>

<div class="max-w-2xl mx-auto">
    <div class="bg-slate-800 border border-slate-600 rounded-2xl shadow-2xl p-10">
        <h2 class="text-3xl font-bold mb-8 bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">👤 Edit Profile</h2>

        <form method="POST" action="/profile" enctype="multipart/form-data" class="space-y-8">
            <!-- Current Avatar Display -->
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="/uploads/avatars/<?= htmlspecialchars($user['avatar']); ?>" alt="Profile Avatar" class="w-32 h-32 rounded-full object-cover border-4 border-cyan-500 shadow-lg">
                    <?php else: ?>
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center border-4 border-slate-600 shadow-lg">
                            <span class="text-5xl">👤</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Avatar Upload -->
            <div>
                <label for="avatar" class="block text-sm font-semibold text-slate-300 mb-3">Profile Picture</label>
                <div class="relative">
                    <input 
                        type="file" 
                        id="avatar" 
                        name="avatar" 
                        accept="image/*"
                        class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer file:bg-blue-600 file:text-white file:border-0 file:rounded-md file:px-4 file:py-2 file:cursor-pointer file:hover:bg-blue-700"
                    />
                </div>
                <p class="text-xs text-slate-400 mt-2">Recommended: Square image (JPG, PNG, GIF)</p>
            </div>

            <!-- Avatar Preview -->
            <div id="avatarPreviewContainer" class="hidden">
                <label class="block text-sm font-semibold text-slate-300 mb-3">Preview</label>
                <div class="bg-slate-700 border border-slate-600 rounded-lg p-4 flex justify-center">
                    <img id="avatarPreview" src="" alt="Avatar preview" class="w-32 h-32 rounded-full object-cover border-4 border-cyan-500">
                </div>
            </div>

            <!-- Bio Section -->
            <div>
                <label for="bio" class="block text-sm font-semibold text-slate-300 mb-3">Bio <span class="text-xs text-slate-400">(max 160 characters)</span></label>
                <textarea 
                    id="bio" 
                    name="bio" 
                    maxlength="160"
                    rows="3"
                    placeholder="Tell us about yourself..."
                    class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                ><?= htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-xs text-slate-400">Bio is optional</p>
                    <p class="text-xs text-slate-400"><span id="charCount">0</span>/160</p>
                </div>
            </div>

            <!-- User Info Display -->
            <div class="bg-slate-700 rounded-lg p-4 border border-slate-600">
                <div class="space-y-2">
                    <p class="text-sm text-slate-300"><span class="font-semibold">Name:</span> <?= htmlspecialchars($user['name']); ?></p>
                    <p class="text-sm text-slate-300"><span class="font-semibold">Email:</span> <?= htmlspecialchars($user['email']); ?></p>
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105"
            >
                Save Changes
            </button>
        </form>

        <!-- Back Link -->
        <div class="mt-6 text-center">
            <a href="/dashboard" class="text-slate-400 hover:text-cyan-400 transition-colors">← Back to Dashboard</a>
        </div>
    </div>
</div>

<script>
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarPreviewContainer = document.getElementById('avatarPreviewContainer');
    const bioInput = document.getElementById('bio');
    const charCount = document.getElementById('charCount');

    // Show avatar preview when image is selected
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(event) {
                avatarPreview.src = event.target.result;
                avatarPreviewContainer.classList.remove('hidden');
            };
            
            reader.readAsDataURL(file);
        }
    });

    // Character counter for bio
    bioInput.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });

    // Initialize character count
    charCount.textContent = bioInput.value.length;
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
