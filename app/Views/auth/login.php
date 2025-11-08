<?php
$title = 'Login | AuthBoard';
ob_start();
?>
    <div class="auth-container">
        <h2>Login</h2>
        <form method="POST" action="/login" class="form">
            <label>Email</label>
            <input type="email" name="email" required />
            <label>Password</label>
            <input type="password" name="password" required />
            <button type="submit">Login</button>
        </form>
        <p class="auth-link">Don’t have an account? <a href="/register" class="btn-logout">Register</a></p>
    </div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>