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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    // Input validation
    if (empty($title) || empty($content)) {
        $error = "Title and content are required!";
    } else {
        // Handle file upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image']['name'];
            $uploadPath = "images/" . basename($image);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $error = "Failed to upload image.";
            }
        }

        // Insert post into the database
        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $title, $content, $image]);
                $success = "Post created successfully!";
                header("Location: dashboard.php");
                exit();
            } catch (Exception $e) {
                $error = "An error occurred while creating the post. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your CSS -->
</head>

<body>
    <h1>Create a New Post</h1>

    <?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
    <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        Title: <input type="text" name="title" required>
        Content: <textarea name="content" required></textarea>
        Image: <input type="file" name="image" accept="image/*">
        <button type="submit">Create Post</button>
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>

</html>