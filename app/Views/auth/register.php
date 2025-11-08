<?php
$title = 'Register | AuthBoard';
ob_start();
?>
    <div class="auth-container">
        <h2>Register</h2>
        <form method="POST" action="/register" class="form">
            <label>Name</label>
            <input type="text" name="name" required />
            <label>Email</label>
            <input type="email" name="email" required />
            <label>Password</label>
            <input type="password" name="password" required />
            <button type="submit">Register</button>
        </form>
        <p class="auth-link">Have an account? <a href="/login" class="btn-logout">Login</a></p>
    </div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>