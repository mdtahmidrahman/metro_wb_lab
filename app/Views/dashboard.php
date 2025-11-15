<?php
$title = 'Dashboard | AuthBoard';
ob_start();
?>

<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-slate-800 to-slate-700 border border-slate-600 rounded-2xl p-8 shadow-xl">
        <h2 class="text-3xl font-bold text-cyan-400 mb-2">Welcome, <?= htmlspecialchars($user['name']); ?>!</h2>
        <p class="text-slate-300 text-lg">Email: <span class="text-cyan-300 font-semibold"><?= htmlspecialchars($user['email']); ?></span></p>
    </div>

    <!-- Create Post Button -->
    <div class="flex justify-center">
        <a href="/post/create" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105 inline-block">
            ✨ Create New Post
        </a>
    </div>

    <!-- Recent Posts Section -->
    <div>
        <h3 class="text-2xl font-bold text-white mb-6">Recent Posts</h3>

        <?php if (!empty($posts)): ?>
            <div class="space-y-6">
                <?php foreach ($posts as $post): ?>
                    <div class="bg-slate-800 border border-slate-600 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:border-slate-500">
                        <!-- Post Header with Avatar -->
                        <div class="mb-4 flex items-start gap-4">
                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
                                <?php if (!empty($post['avatar'])): ?>
                                    <img src="/uploads/avatars/<?= htmlspecialchars($post['avatar']); ?>" alt="<?= htmlspecialchars($post['name']); ?>" class="w-12 h-12 rounded-full object-cover border-2 border-cyan-500">
                                <?php else: ?>
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-cyan-400 flex items-center justify-center text-lg border-2 border-slate-600">👤</div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- User Info + Actions -->
                            <div class="flex-1 flex items-start justify-between">
                                <div>
                                    <h4 class="text-lg font-bold text-cyan-300"><?= htmlspecialchars($post['name']); ?></h4>
                                    <?php if (!empty($post['bio'])): ?>
                                        <p class="text-sm text-slate-400 italic"><?= htmlspecialchars($post['bio']); ?></p>
                                    <?php endif; ?>
                                    <p class="text-xs text-slate-500 mt-1">📅 <?= htmlspecialchars($post['created_at']); ?></p>
                                </div>

                                <?php if (!empty($user) && (int)$user['id'] === (int)$post['user_id']): ?>
                                    <div class="flex-shrink-0 ml-4">
                                        <button type="button" class="delete-btn px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm" data-post-id="<?= $post['id'] ?>">Delete</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Post Content -->
                        <div class="mb-4 ml-16">
                            <p class="text-slate-200 leading-relaxed"><?= nl2br(htmlspecialchars($post['content'])); ?></p>
                        </div>

                        <!-- Post Image -->
                        <?php if (!empty($post['image'])): ?>
                            <div class="mb-4 rounded-lg overflow-hidden border border-slate-600">
                                <img src="/uploads/<?= htmlspecialchars($post['image']); ?>"
                                     alt="Post Image"
                                     class="w-full h-auto object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        <?php endif; ?>

                        <!-- Vote Section -->
                        <div class="vote-box flex gap-3 mt-6 pt-4 border-t border-slate-600" data-post-id="<?= $post['id'] ?>">
                            <!-- Upvote -->
                            <button class="vote-btn upvote<?= $post['user_vote'] == 1 ? ' active' : '' ?> flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-emerald-400 font-semibold rounded-lg transition-all duration-200 flex flex-col items-center justify-center gap-1 border border-slate-600 hover:border-emerald-500" data-vote="1">
                                <span class="text-xl">▲</span>
                                <span class="up-count text-sm"><?= $post['upvotes'] ?></span>
                                <span class="vote-badge up hidden">Upvoted</span>
                            </button>

                            <!-- Downvote -->
                            <button class="vote-btn downvote<?= $post['user_vote'] == -1 ? ' active' : '' ?> flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-rose-400 font-semibold rounded-lg transition-all duration-200 flex flex-col items-center justify-center gap-1 border border-slate-600 hover:border-rose-500" data-vote="-1">
                                <span class="text-xl">▼</span>
                                <span class="down-count text-sm"><?= $post['downvotes'] ?></span>
                                <span class="vote-badge down hidden">Downvoted</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-slate-800 border-2 border-dashed border-slate-600 rounded-2xl p-12 text-center">
                <p class="text-slate-400 text-lg">No posts yet. Be the first to post something!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        // Helper to update visual state of a vote box
        function applyVisualState(voteBox, finalVote) {
            const upBtn = voteBox.querySelector('.upvote');
            const downBtn = voteBox.querySelector('.downvote');
            const upCount = voteBox.querySelector('.up-count');
            const downCount = voteBox.querySelector('.down-count');
            const upBadge = voteBox.querySelector('.vote-badge.up');
            const downBadge = voteBox.querySelector('.vote-badge.down');

            // Reset styles
            upCount.classList.remove('text-emerald-400','font-semibold');
            downCount.classList.remove('text-rose-400','font-semibold');
            upBtn.setAttribute('aria-pressed', 'false');
            downBtn.setAttribute('aria-pressed', 'false');

            // Hide badges by default
            if (upBadge) { upBadge.classList.remove('visible'); upBadge.classList.add('hidden'); }
            if (downBadge) { downBadge.classList.remove('visible'); downBadge.classList.add('hidden'); }

            if (finalVote === 1) {
                upCount.classList.add('text-emerald-400','font-semibold');
                upBtn.setAttribute('aria-pressed', 'true');
                if (upBadge) { upBadge.classList.remove('hidden'); setTimeout(() => upBadge.classList.add('visible'), 10); }
            } else if (finalVote === -1) {
                downCount.classList.add('text-rose-400','font-semibold');
                downBtn.setAttribute('aria-pressed', 'true');
                if (downBadge) { downBadge.classList.remove('hidden'); setTimeout(() => downBadge.classList.add('visible'), 10); }
            }
        }

        // Initialize visuals based on server-rendered active classes
        document.querySelectorAll('.vote-box').forEach(vb => {
            const upActive = vb.querySelector('.upvote')?.classList.contains('active');
            const downActive = vb.querySelector('.downvote')?.classList.contains('active');
            if (upActive) applyVisualState(vb, 1);
            else if (downActive) applyVisualState(vb, -1);
            else applyVisualState(vb, 0);
        });

        // Main event wiring
        document.querySelectorAll('.vote-box').forEach(voteBox => {
            const postId = voteBox.dataset.postId;

            voteBox.querySelectorAll('.vote-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const chosenVote = parseInt(btn.dataset.vote);
                    const upBtn = voteBox.querySelector('.upvote');
                    const downBtn = voteBox.querySelector('.downvote');

                    let finalVote = chosenVote;
                    // Withdraw if clicking the same vote again
                    if (btn.classList.contains('active')) {
                        finalVote = 0;
                    }

                    // Send AJAX request (no page reload)
                    try {
                        const response = await fetch('/post/vote', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ postId, vote: finalVote })
                        });

                        const data = await response.json();

                        // Update counts
                        voteBox.querySelector('.up-count').innerText = data.upvotes;
                        voteBox.querySelector('.down-count').innerText = data.downvotes;

                        // Update active classes
                        upBtn.classList.toggle('active', finalVote === 1);
                        downBtn.classList.toggle('active', finalVote === -1);

                        // Apply visual styling (colors, bold)
                        applyVisualState(voteBox, finalVote);
                    } catch (err) {
                        console.error('Vote failed', err);
                    }
                });
            });
        });
    });
</script>

<!-- Hidden delete form used by modal -->
<form id="deleteForm" method="POST" action="/post/delete" class="hidden">
    <input type="hidden" name="postId" id="deletePostId" value="">
</form>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/60" id="confirmModalOverlay"></div>

    <div class="relative bg-slate-800 rounded-xl p-6 max-w-md w-full border border-slate-600 shadow-xl mx-4">
        <h3 class="text-lg font-semibold text-white">Delete post?</h3>
        <p class="text-sm text-slate-300 mt-2">This action cannot be undone. Are you sure you want to delete this post?</p>

        <div class="mt-6 flex justify-end gap-3">
            <button id="confirmCancel" type="button" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-md">Cancel</button>
            <button id="confirmDelete" type="button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">Yes, delete</button>
        </div>
    </div>
</div>

<script>
    (function() {
        const modal = document.getElementById('confirmModal');
        const overlay = document.getElementById('confirmModalOverlay');
        const cancelBtn = document.getElementById('confirmCancel');
        const deleteBtn = document.getElementById('confirmDelete');
        const deleteForm = document.getElementById('deleteForm');
        const deletePostId = document.getElementById('deletePostId');

        // Open modal when any .delete-btn is clicked
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.postId;
                deletePostId.value = id;
                // show modal as a flex container to center content
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                // focus confirm button for accessibility
                deleteBtn.focus();
            });
        });

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            deletePostId.value = '';
        }

        overlay.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        deleteBtn.addEventListener('click', function() {
            // Submit the hidden form
            deleteForm.submit();
        });
    })();
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
