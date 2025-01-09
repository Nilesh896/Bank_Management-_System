<?php
require 'db/db_connect.php'; // Database connection

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Raw password input

    // Validate if the user exists
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password using password_verify()
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php"); // Redirect to dashboard
            exit; // Stop further execution
        } else {
            $error = "Invalid login credentials!";
        }
    } else {
        $error = "Invalid login credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<div class="container">
    <form class="login-form" method="POST" action="index.php"> <!-- Corrected action to point to index.php -->
        <h2>Login to Your Account</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="show-password">
            <input type="checkbox" id="show-password" onclick="togglePassword()"> Show Password
        </div>
        <button type="submit">Login</button>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?> <!-- Display error message -->
        <p class="register-link">Don't have an account? <a href="register.php">Register here.</a></p>
    </form>
</div>
<script>
    function togglePassword() {
        const passwordField = document.querySelector('input[name="password"]');
        passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
    }
</script>
</body>
</html>
