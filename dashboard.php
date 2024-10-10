<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$posts = $stmt->fetchAll();
?>

<h1>Your Posts</h1>
<a href="create_post.php">Create New Post</a>
<?php foreach ($posts as $post): ?>
<h2><?= htmlspecialchars($post['title']) ?></h2>
<p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
<a href="post.php?id=<?= $post['id'] ?>">View</a>
<a href="edit_post.php?id=<?= $post['id'] ?>">Edit</a>
<a href="delete_post.php?id=<?= $post['id'] ?>">Delete</a>
<?php endforeach; ?>