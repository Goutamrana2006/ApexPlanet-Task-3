<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET["id"];

$message = "";

// Fetch existing post
$stmt = $conn->prepare(
    "SELECT id, title, content FROM posts WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: index.php");
    exit();
}

$post = $result->fetch_assoc();

$stmt->close();

// Update post
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if (empty($title) || empty($content)) {

        $message = "Please fill all fields.";

    } else {

        $stmt = $conn->prepare(
            "UPDATE posts SET title = ?, content = ? WHERE id = ?"
        );

        $stmt->bind_param("ssi", $title, $content, $id);

        if ($stmt->execute()) {

            header("Location: index.php");
            exit();

        } else {

            $message = "Failed to update post.";
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

    <title>Edit Post</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>

<div class="container">

    <h1>Edit Post</h1>

    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Title</label>

        <input
            type="text"
            name="title"
            value="<?php echo htmlspecialchars($post["title"]); ?>"
            required
        >

        <label>Content</label>

        <textarea
            name="content"
            rows="8"
            required
        ><?php echo htmlspecialchars($post["content"]); ?></textarea>

        <button type="submit">
            Update Post
        </button>

    </form>

    <br>

    <a href="index.php">Back to Posts</a>

</div>

</body>
</html>