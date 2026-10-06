<?php

session_start();

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $message = "Please fill all fields.";
    } else {

        // Check if username already exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "Username already exists.";

        } else {

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $conn->prepare(
                "INSERT INTO users (username, password) VALUES (?, ?)"
            );

            $stmt->bind_param("ss", $username, $hashedPassword);

            if ($stmt->execute()) {
                $message = "Registration successful! You can now login.";
            } else {
                $message = "Registration failed.";
            }

            $stmt->close();
        }

        $check->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ApexPlanet Task 2</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

<div class="container">

    <h1>Create Account</h1>

    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Register</button>

    </form>

    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</div>

</body>
</html>