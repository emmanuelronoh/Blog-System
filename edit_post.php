<?php
session_start();
require 'db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize feedback messages
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postId = $_POST['post_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Input validation
    if (empty($title) || empty($content)) {
        $error = "Title and content are required!";
    } else {
        // Update the post in the database
        try {
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $content, $postId, $_SESSION['user_id']]);
            $success = "Post updated successfully!";
            header("Location: dashboard.php");
            exit();
        } catch (Exception $e) {
            $error = "An error occurred while updating the post. Please try again.";
        }
    }
}

// Fetch the post to edit
$postId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$postId, $_SESSION['user_id']]);
$post = $stmt->fetch();

if (!$post) {
    echo "Post not found or you don't have permission to edit it.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your CSS -->
</head>

<body>
    <h1>Edit Post</h1>

    <?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
    <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($postId) ?>">
        Title: <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        Content: <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
        <button type="submit">Update Post</button>
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>

</html>