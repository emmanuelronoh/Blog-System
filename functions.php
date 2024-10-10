<?php
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function redirect($url)
{
    header("Location: $url");
    exit();
}

function sanitize($data)
{
    return htmlspecialchars(strip_tags($data));
}

// Example function to fetch posts by user
function getUserPosts($userId, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

// Example function to get a single post
function getPost($postId, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$postId]);
    return $stmt->fetch();
}