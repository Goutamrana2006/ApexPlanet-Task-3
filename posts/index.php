<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

/* =========================
   SEARCH
========================= */

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

/* =========================
   PAGINATION
========================= */

$postsPerPage = 5;

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $postsPerPage;

/* =========================
   COUNT POSTS
========================= */

if ($search !== "") {

    $searchTerm = "%" . $search . "%";

    $countStmt = $conn->prepare(
        "SELECT COUNT(*) AS total
         FROM posts
         WHERE title LIKE ? OR content LIKE ?"
    );

    $countStmt->bind_param("ss", $searchTerm, $searchTerm);
    $countStmt->execute();

    $countResult = $countStmt->get_result();
    $totalPosts = $countResult->fetch_assoc()["total"];

    $countStmt->close();

} else {

    $countResult = $conn->query(
        "SELECT COUNT(*) AS total FROM posts"
    );

    $totalPosts = $countResult->fetch_assoc()["total"];
}

/* =========================
   TOTAL PAGES
========================= */

$totalPages = ceil($totalPosts / $postsPerPage);

/* =========================
   FETCH POSTS
========================= */

if ($search !== "") {

    $stmt = $conn->prepare(
        "SELECT id, title, content, created_at
         FROM posts
         WHERE title LIKE ? OR content LIKE ?
         ORDER BY created_at DESC
         LIMIT ? OFFSET ?"
    );

    $stmt->bind_param(
        "ssii",
        $searchTerm,
        $searchTerm,
        $postsPerPage,
        $offset
    );

    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $stmt = $conn->prepare(
        "SELECT id, title, content, created_at
         FROM posts
         ORDER BY created_at DESC
         LIMIT ? OFFSET ?"
    );

    $stmt->bind_param(
        "ii",
        $postsPerPage,
        $offset
    );

    $stmt->execute();

    $result = $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Posts - ApexPlanet Task 3</title>

    <link rel="stylesheet" href="../style.css">

</head>

<body>

<div class="container">

    <h1>My Posts</h1>

    <p>
        Welcome,
        <strong>
            <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </strong>
    </p>

    <!-- Navigation -->

    <a href="create.php">Create New Post</a> |

    <a href="../auth/logout.php">Logout</a>

    <hr>

    <!-- Search Form -->

    <form method="GET" action="index.php">

        <label for="search">Search Posts</label>

        <input
            type="text"
            id="search"
            name="search"
            placeholder="Search by title or content..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">
            Search
        </button>

        <?php if ($search !== ""): ?>

            <a href="index.php">
                Clear Search
            </a>

        <?php endif; ?>

    </form>

    <hr>

    <!-- Posts -->

    <?php if ($result->num_rows > 0): ?>

        <?php while ($post = $result->fetch_assoc()): ?>

            <article>

                <h2>
                    <?php echo htmlspecialchars($post["title"]); ?>
                </h2>

                <p>
                    <?php
                    echo nl2br(
                        htmlspecialchars($post["content"])
                    );
                    ?>
                </p>

                <small>

                    Created:
                    <?php
                    echo htmlspecialchars(
                        $post["created_at"]
                    );
                    ?>

                </small>

                <br><br>

                <a href="edit.php?id=<?php echo $post["id"]; ?>">
                    Edit
                </a>

                |

                <a
                    href="delete.php?id=<?php echo $post["id"]; ?>"
                    onclick="return confirm('Are you sure you want to delete this post?');"
                >
                    Delete
                </a>

            </article>

            <hr>

        <?php endwhile; ?>

    <?php else: ?>

        <p>
            <?php if ($search !== ""): ?>

                No posts found for:
                <strong>
                    <?php echo htmlspecialchars($search); ?>
                </strong>

            <?php else: ?>

                No posts available.

            <?php endif; ?>
        </p>

    <?php endif; ?>


    <!-- Pagination -->

    <?php if ($totalPages > 1): ?>

        <div class="pagination">

            <?php if ($page > 1): ?>

                <a
                    href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>"
                >
                    &laquo; Previous
                </a>

            <?php endif; ?>


            <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                <a
                    href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"
                    class="<?php echo ($i == $page) ? 'active' : ''; ?>"
                >
                    <?php echo $i; ?>
                </a>

            <?php endfor; ?>


            <?php if ($page < $totalPages): ?>

                <a
                    href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>"
                >
                    Next &raquo;
                </a>

            <?php endif; ?>

        </div>

    <?php endif; ?>

</div>

</body>

</html>