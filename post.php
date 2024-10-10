<?php
session_start();
require 'db.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$postId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch();

if (!$post) {
    echo "Post not found.";
    exit();
}

$commentsStmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC");
$commentsStmt->execute([$postId]);
$comments = $commentsStmt->fetchAll();
?>

<h1><?= htmlspecialchars($post['title']) ?></h1>
<p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
<?php if ($post['image']): ?>
<img src="images/<?= htmlspecialchars($post['image']) ?>" alt="Post image">
<?php endif; ?>

<h3>Comments</h3>
<?php foreach ($comments as $comment): ?>
<p><strong>User <?= htmlspecialchars($comment['user_id']) ?>:</strong> <?= htmlspecialchars($comment['content']) ?></p>
<?php endforeach; ?>

<?php if (isset($_SESSION['user_id'])): ?>
<form method="post" action="comments.php">
    <input type="hidden" name="post_id" value="<?= $postId ?>">
    <textarea name="content" required></textarea>
    <button type="submit">Add Comment</button>
</form>
<?php endif; ?>