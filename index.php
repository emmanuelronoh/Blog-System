<?php
session_start();
require 'db.php';

// Fetch recent posts
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header>
        <h1>Welcome to My Blog</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
        <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <?php endif; ?>
    </header>

    <main>
        <h2>Recent Posts</h2>
        <?php foreach ($posts as $post): ?>
        <h3><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        <p><em>Posted on <?= $post['created_at'] ?></em></p>
        <?php endforeach; ?>

        <?php if (empty($posts)): ?>
        <p>No posts available.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> My Blog</p>
    </footer>
</body>

</html>