<?php
require 'db/db_connect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing
    $email = $_POST['email'];
    $balance = $_POST['balance'];

    // Validate balance is numeric and positive
    if (!is_numeric($balance) || $balance < 0) {
        $error = "Balance must be a positive number!";
    } else {
        // Validate if username or email already exists
        $checkUser = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $checkUser->bind_param("ss", $username, $email);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows > 0) {
            $error = "Username or Email already taken!";
        } else {
            // Insert new user
            $insertUser = $conn->prepare("INSERT INTO users (username, password, email, balance) VALUES (?, ?, ?, ?)");
            $insertUser->bind_param("sssd", $username, $password, $email, $balance);

            if ($insertUser->execute()) {
                header("Location: index.php"); // Redirect to login page
                exit;
            } else {
                $error = "Error registering user: " . $conn->error;
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
    <title>Register</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
<div class="container">
    <form method="POST" action="register.php">
        <h2>Create Account</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <table class="registration-table">
            <tr>
                <th colspan="2">Registration Form</th>
            </tr>
            <tr>
                <td><label for="username">Username:</label></td>
                <td><input type="text" id="username" name="username" placeholder="Enter your username" required></td>
            </tr>
            <tr>
                <td><label for="password">Password:</label></td>
                <td><input type="password" id="password" name="password" placeholder="Enter your password" required></td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td><input type="email" id="email" name="email" placeholder="Enter your email" required></td>
            </tr>
            <tr>
                <td><label for="balance">Initial Balance:</label></td>
                <td><input type="number" id="balance" name="balance" placeholder="Enter initial balance" required></td>
            </tr>
        </table>
        <button type="submit">Register</button>
        <p class="login-link">Already have an account? <a href="index.php">Login here</a></p>
    </form>
</div>

</body>

</html>
