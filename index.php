<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <h1>Student Management System</h1>

    <p>
        A PHP and MySQL-based application for managing student records
        with secure authentication and CRUD functionality.
    </p>

    <?php if (isset($_SESSION['user_id'])): ?>

        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>

        <p>
            <a href="posts/index.php">Manage Posts</a> |
            <a href="auth/logout.php">Logout</a>
        </p>

    <?php else: ?>

        <p>
            <a href="auth/login.php">Login</a> |
            <a href="auth/register.php">Register</a>
        </p>

    <?php endif; ?>

</div>

</body>
</html>