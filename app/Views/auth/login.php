<?php
$title = 'Login | AuthBoard';
ob_start();
?>
<div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
    <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl p-8">
        <h2 class="text-3xl font-bold text-center mb-8 bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">Login</h2>
        
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 bg-red-900 border border-red-700 rounded-lg text-red-200">
                ⚠️ <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/login" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter your email" />
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter your password" />
            </div>
            
            <button type="submit" class="w-full mt-6 px-4 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold rounded-lg transition-all duration-200 transform hover:scale-105">Login</button>
        </form>
        
        <p class="auth-link text-center mt-6 text-slate-400">Don't have an account? <a href="/register" class="text-cyan-400 hover:text-cyan-300 font-semibold transition-colors">Register</a></p>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>