<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$result = $conn->query(
    "SELECT id, title, content, created_at 
     FROM posts 
     ORDER BY created_at DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Posts - ApexPlanet Task 2</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>

<div class="container">

    <h1>My Posts</h1>

    <p>
        Welcome,
        <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>
    </p>

    <a href="create.php">Create New Post</a> |
    <a href="../auth/logout.php">Logout</a>

    <hr>

    <?php if ($result->num_rows > 0): ?>

        <?php while ($post = $result->fetch_assoc()): ?>

            <article>

                <h2>
                    <?php echo htmlspecialchars($post["title"]); ?>
                </h2>

                <p>
                    <?php echo nl2br(htmlspecialchars($post["content"])); ?>
                </p>

                <small>
                    Created:
                    <?php echo htmlspecialchars($post["created_at"]); ?>
                </small>

                <br><br>

                <a href="edit.php?id=<?php echo $post["id"]; ?>">
                    Edit
                </a>

                |

                <a href="delete.php?id=<?php echo $post["id"]; ?>"
                   onclick="return confirm('Are you sure you want to delete this post?');">
                    Delete
                </a>

            </article>

            <hr>

        <?php endwhile; ?>

    <?php else: ?>

        <p>No posts available.</p>

    <?php endif; ?>

</div>

</body>
</html>