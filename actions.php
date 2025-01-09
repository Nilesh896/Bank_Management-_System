<?php
session_start();
require 'db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $amount = floatval($_POST['amount']);
    $action = $_POST['action'];

    // Validate input
    if ($amount <= 0 || !is_numeric($amount)) {
        $_SESSION['error'] = "Invalid amount entered.";
        header('Location: dashboard.php'); // Replace with your dashboard or main page
        exit;
    }

    // Use prepared statements to fetch the user's balance
    $stmt = $conn->prepare("SELECT balance FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $balance = $user['balance'];

        // Perform action based on 'deposit' or 'withdraw'
        if ($action === 'deposit') {
            $newBalance = $balance + $amount;
        } elseif ($action === 'withdraw') {
            if ($balance >= $amount) {
                $newBalance = $balance - $amount;
            } else {
                $_SESSION['error'] = "Insufficient funds.";
                header('Location: index.php'); // Replace with your dashboard or main page
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid action.";
            header('Location: index.php'); // Replace with your dashboard or main page
            exit;
        }

        // Use prepared statements to update the user's balance
        $updateStmt = $conn->prepare("UPDATE users SET balance = ? WHERE username = ?");
        $updateStmt->bind_param("ds", $newBalance, $username);

        if ($updateStmt->execute()) {
            $_SESSION['success'] = "Transaction successful.";
        } else {
            $_SESSION['error'] = "Error updating balance.";
        }

        $updateStmt->close();
    } else {
        $_SESSION['error'] = "User not found.";
    }

    $stmt->close();
    header('Location: index.php'); // Replace with your dashboard or main page
    exit;
}
?>
