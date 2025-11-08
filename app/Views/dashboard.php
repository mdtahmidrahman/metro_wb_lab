<?php
$title = 'Dashboard | AuthBoard';
ob_start();
?>

<div class="dashboard">
    <h2>Welcome, <?= htmlspecialchars($user['name']); ?>!</h2>
    <p>Your email: <?= htmlspecialchars($user['email']); ?></p>

    <hr>

    <a href="/post/create" class="btn">Create New Post</a>

    <hr>

    <h3>Recent Posts</h3>

    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post-box">
                <h2><?= htmlspecialchars($post['name']); ?></h2>
                <p><?= nl2br(htmlspecialchars($post['content'])); ?></p>

                <?php if (!empty($post['image'])): ?>
                    <img src="/uploads/<?= htmlspecialchars($post['image']); ?>"
                         alt="Post Image"
                         style="max-width: 300px; border-radius: 10px;">
                <?php endif; ?>

                <small>Posted on <?= htmlspecialchars($post['created_at']); ?></small>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts yet. Be the first to post something!</p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
