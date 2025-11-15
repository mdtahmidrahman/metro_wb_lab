<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'AuthBoard' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-slate-100 min-h-screen">

<div class="min-h-screen flex flex-col">
    <header class="bg-slate-950 border-b border-slate-700 shadow-2xl sticky top-0 z-50">
        <div class="w-full px-8 py-6 flex justify-between items-center">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">AuthBoard</h1>

            <?php if (!empty($_SESSION['user'])): ?>
                <nav class="flex gap-8 items-center">
                    <a href="/dashboard" class="text-slate-300 hover:text-cyan-400 transition-colors duration-200 font-medium text-lg">Dashboard</a>
                    <a href="/profile" class="flex items-center gap-2 text-slate-300 hover:text-cyan-400 transition-colors duration-200 font-medium">
                        <?php if (!empty($_SESSION['user']['avatar'])): ?>
                            <img src="/uploads/avatars/<?= htmlspecialchars($_SESSION['user']['avatar']); ?>" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-cyan-400">
                        <?php else: ?>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-cyan-400 flex items-center justify-center text-xs">👤</div>
                        <?php endif; ?>
                        Profile
                    </a>
                    <a href="/logout" class="px-6 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200 font-medium">Logout</a>
                </nav>
            <?php endif; ?>
        </div>
    </header>

    <main class="flex-1">
        <div class="max-w-5xl mx-auto px-6 py-8">
            <?= $content ?? '' ?>
        </div>
    </main>

    <footer class="bg-slate-950 border-t border-slate-700 mt-12">
        <div class="max-w-5xl mx-auto px-6 py-6 text-center text-slate-400 text-sm">
            <small>AuthBoard - Teaching Project</small>
        </div>
    </footer>
</div>

</body>
</html>
