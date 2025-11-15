<?php
namespace App\Models;

use PDO;

class Post
{
    private static function connect(): PDO
    {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $db   = getenv('DB_NAME') ?: 'authboard';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public static function create(int $userId, string $content, ?string $image = null): bool {
        $stmt = self::connect()->prepare(
            "INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$userId, $content, $image]);
    }

    public function updateVote($postId, $userId, $vote)
    {
        $pdo = self::connect();

        // Check if user already voted
        $stmt = $pdo->prepare("SELECT vote FROM post_votes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
        $existing = $stmt->fetch();

        // HANDLE WITHDRAW
        if ($vote == 0 && $existing) {

            // Decrease previous vote count
            if ($existing['vote'] == 1) {
                $pdo->prepare("UPDATE posts SET upvotes = upvotes - 1 WHERE id = ?")->execute([$postId]);
            } else {
                $pdo->prepare("UPDATE posts SET downvotes = downvotes - 1 WHERE id = ?")->execute([$postId]);
            }

            // Remove vote record
            $pdo->prepare("DELETE FROM post_votes WHERE post_id = ? AND user_id = ?")
                ->execute([$postId, $userId]);

            return $this->getVoteCounts($postId);
        }

        if ($existing) {
            // If same vote, do nothing, return counts
            if ((int)$existing['vote'] === $vote) {
                return $this->getVoteCounts($postId);
            }

            // Undo previous vote
            if ($existing['vote'] == 1) {
                $pdo->prepare("UPDATE posts SET upvotes = upvotes - 1 WHERE id = ?")->execute([$postId]);
            } else {
                $pdo->prepare("UPDATE posts SET downvotes = downvotes - 1 WHERE id = ?")->execute([$postId]);
            }

            // Update vote
            $pdo->prepare("UPDATE post_votes SET vote = ? WHERE post_id = ? AND user_id = ?")
                ->execute([$vote, $postId, $userId]);
        } else {
            // New vote
            $pdo->prepare("INSERT INTO post_votes (post_id, user_id, vote) VALUES (?, ?, ?)")
                ->execute([$postId, $userId, $vote]);
        }

        // Apply new vote
        if ($vote == 1) {
            $pdo->prepare("UPDATE posts SET upvotes = upvotes + 1 WHERE id = ?")->execute([$postId]);
        } else {
            $pdo->prepare("UPDATE posts SET downvotes = downvotes + 1 WHERE id = ?")->execute([$postId]);
        }

        return $this->getVoteCounts($postId);
    }

    private function getVoteCounts($postId)
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT upvotes, downvotes FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetch();
    }

    public static function getAllWithUser($currentUserId = null): array {
        $pdo = self::connect();

        $posts = $pdo->query("
        SELECT posts.*, users.name, users.avatar, users.bio
        FROM posts
        JOIN users ON posts.user_id = users.id
        ORDER BY posts.created_at DESC
    ")->fetchAll();

        // Add current user's vote info if logged in
        if ($currentUserId) {
            foreach ($posts as &$post) {
                $stmt = $pdo->prepare("SELECT vote FROM post_votes WHERE post_id = ? AND user_id = ?");
                $stmt->execute([$post['id'], $currentUserId]);
                $vote = $stmt->fetchColumn();
                $post['user_vote'] = $vote ? (int)$vote : 0;
            }
        }

        return $posts;
    }

    public static function findById(int $id): ?array {
        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function deleteById(int $id): bool {
        $pdo = self::connect();
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([$id]);
    }

}
