<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Post;

class PostController extends Controller
{
    public function create(): void
    {
        $user = Session::get('user');
        if (!$user)
        {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $content = trim($_POST['content']);
            $imagePath = null;

            if (!empty($_FILES['image']['name']))
            {
                $uploadDir = __DIR__ . '/../../public/uploads/';

                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }

                $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $allowedExt))
                {
                    $imageName = time() . '_' . preg_replace("/[^a-zA-Z0-9_-]/", "", basename($_FILES['image']['name']));
                    $targetFile = $uploadDir . $imageName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile))
                    {
                        $imagePath = $imageName;
                    }
                }
            }

            Post::create($user['id'], $content, $imagePath);

            header('Location: /dashboard');
            exit;
        }

        $this->view('create_post.php', ['user' => $user]);
    }

    public function vote()
    {
        header('Content-Type: application/json');

        $user = Session::get('user');
        if (!$user) {
            echo json_encode(['error' => 'Not logged in']);
            return;
        }

        // Read JSON payload
        $input = json_decode(file_get_contents('php://input'), true);
        $postId = $input['postId'] ?? null;
        $vote = (int) ($input['vote'] ?? 0);

        if (!$postId || !in_array($vote, [1, -1, 0])) {
            echo json_encode(['error' => 'Invalid vote']);
            return;
        }

        $postModel = new Post();
        $result = $postModel->updateVote($postId, $user['id'], $vote);

        echo json_encode([
            'success' => true,
            'upvotes' => $result['upvotes'],
            'downvotes' => $result['downvotes']
        ]);
    }

    public function delete()
    {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $postId = (int) ($_POST['postId'] ?? 0);
        if (!$postId) {
            header('Location: /dashboard');
            exit;
        }

        // Ensure the post exists and belongs to the current user
        $post = Post::findById($postId);
        if (!$post) {
            header('Location: /dashboard');
            exit;
        }

        if ((int)$post['user_id'] !== (int)$user['id']) {
            // Not allowed
            header('Location: /dashboard');
            exit;
        }

        // Remove image file if present
        if (!empty($post['image'])) {
            $imagePath = __DIR__ . '/../../public/uploads/' . $post['image'];
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }

        // Delete the post
        Post::deleteById($postId);

        header('Location: /dashboard');
        exit;
    }
}
