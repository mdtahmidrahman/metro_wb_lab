<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$key, $val] = array_map('trim', explode('=', $line, 2) + [1=>null]);
        if ($key && $val !== null) {
            putenv("$key=$val");
            $_ENV[$key] = $val;
        }
    }
}

use App\Core\Router;
use App\Core\Session;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\PostController;

Session::start();

$router = new Router();
$auth   = new AuthController();
$dash   = new DashboardController();
$post   = new PostController();

$router->get('/', fn() => $auth->showLogin());
$router->get('/login', fn() => $auth->showLogin());
$router->get('/register', fn() => $auth->showRegister());
$router->post('/login', fn() => $auth->login());
$router->post('/register', fn() => $auth->register());
$router->get('/logout', fn() => $auth->logout());

$router->get('/dashboard', fn() => $dash->index());
$router->get('/profile', fn() => $dash->profile());
$router->post('/profile', fn() => $dash->profile());

$router->get('/post/create', fn() => $post->create());
$router->post('/post/create', fn() => $post->create());
$router->post('/post/delete', fn() => $post->delete());


$router->post('/post/vote', function () use ($post) {

    // Read JSON body from fetch()
    $data = json_decode(file_get_contents("php://input"), true);

    // Pass to controller
    return $post->vote($data);
});

$router->dispatch(
    $_SERVER['REQUEST_URI'] ?? '/',
    $_SERVER['REQUEST_METHOD'] ?? 'GET'
);
