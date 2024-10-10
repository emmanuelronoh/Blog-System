<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $postId = $_POST['post_id'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->execute([$postId, $_SESSION['user_id'], $content]);

    header("Location: post.php?id=$postId");
    exit();
} else {
    header("Location: login.php");
    exit();
}