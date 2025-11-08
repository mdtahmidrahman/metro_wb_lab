<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'AuthBoard' ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>

<div class="container">
    <header>
        <h1>AuthBoard</h1>

        <?php if (!empty($_SESSION['user'])): ?>
            <nav>
                <a href="/dashboard" class="nav-link">Dashboard</a>
                <a href="/logout" class="nav-link">Logout</a>
            </nav>
        <?php endif; ?>
    </header>

    <main>
        <?= $content ?? '' ?>
    </main>

    <footer>
        <small>AuthBoard - teaching project</small>
    </footer>

</div>

</body>
</html>
