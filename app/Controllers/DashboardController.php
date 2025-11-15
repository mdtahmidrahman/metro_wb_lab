<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Post;
use App\Models\User;

class DashboardController extends Controller {
    public function index(): void
    {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $posts = Post::getAllWithUser($user['id']);

        $this->view('dashboard.php', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function profile(): void
    {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bio = trim($_POST['bio'] ?? '');
            $avatarPath = null;

            // Handle avatar upload
            if (!empty($_FILES['avatar']['name'])) {
                $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
                $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $allowedExt)) {
                    $avatarName = 'avatar_' . $user['id'] . '_' . time() . '.' . $ext;
                    $targetFile = $uploadDir . $avatarName;

                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
                        $avatarPath = $avatarName;
                    }
                }
            }

            // Update profile
            User::updateProfile($user['id'], $avatarPath, $bio);
            
            // Update session
            $user['bio'] = $bio;
            if ($avatarPath) {
                $user['avatar'] = $avatarPath;
            }
            Session::set('user', $user);

            header('Location: /profile');
            exit;
        }

        $this->view('profile.php', ['user' => $user]);
    }
}
