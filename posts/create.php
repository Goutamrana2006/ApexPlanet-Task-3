<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if (empty($title) || empty($content)) {

        $message = "Please fill all fields.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO posts (title, content) VALUES (?, ?)"
        );

        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {

            header("Location: index.php");
            exit();

        } else {

            $message = "Failed to create post.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Post</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>

<div class="container">

    <h1>Create New Post</h1>

    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Title</label>

        <input
            type="text"
            name="title"
            required
        >

        <label>Content</label>

        <textarea
            name="content"
            rows="8"
            required
        ></textarea>

        <button type="submit">
            Create Post
        </button>

    </form>

    <br>

    <a href="index.php">Back to Posts</a>

</div>

</body>
</html>