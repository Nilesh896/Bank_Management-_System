<?php
require 'db/db_connect.php'; // Database connection

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php'); // Redirect to login if not logged in
    exit;
}

$username = $_SESSION['username'];
$userQuery = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();

$balance = $user['balance'];

// Handle Withdraw or Deposit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $action = $_POST['action'];
    $enteredPassword = $_POST['password']; // Raw password input

    // Validate password
    if (!password_verify($enteredPassword, $user['password'])) {
        $error = "Incorrect password!";
    } else {
        if ($action === 'withdraw' && $amount <= $balance) {
            $newBalance = $balance - $amount;
            $updateQuery = "UPDATE users SET balance = ? WHERE username = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ds", $newBalance, $username);
            if ($stmt->execute()) {
                // Insert transaction into history
                $insertTransaction = "INSERT INTO transactions (user_id, type, amount) VALUES (?, 'withdraw', ?)";
                $stmt = $conn->prepare($insertTransaction);
                $stmt->bind_param("id", $user['id'], $amount);
                $stmt->execute();
                $_SESSION['balance'] = $newBalance;
                header('Location: dashboard.php');
                exit;
            }
        } elseif ($action === 'deposit') {
            $newBalance = $balance + $amount;
            $updateQuery = "UPDATE users SET balance = ? WHERE username = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ds", $newBalance, $username);
            if ($stmt->execute()) {
                // Insert transaction into history
                $insertTransaction = "INSERT INTO transactions (user_id, type, amount) VALUES (?, 'deposit', ?)";
                $stmt = $conn->prepare($insertTransaction);
                $stmt->bind_param("id", $user['id'], $amount);
                $stmt->execute();
                $_SESSION['balance'] = $newBalance;
                header('Location: dashboard.php');
                exit;
            }
        } else {
            $error = "Invalid amount or insufficient balance!";
        }
    }
}

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php'); // Redirect to login page
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div class="container dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Your current balance: ₹<?php echo number_format($balance, 2); ?></p>
        
        <form method="POST" class="transaction-form">
            <input type="number" name="amount" placeholder="Enter Amount" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <div class="actions">
                <button type="submit" name="action" value="withdraw">Withdraw</button>
                <button type="submit" name="action" value="deposit">Deposit</button>
            </div>
        </form>

        <?php if (isset($error)) { echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; } ?>

        <h3>Transaction History</h3>
        <table class="transaction-table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $transactionQuery = "SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC";
                $stmt = $conn->prepare($transactionQuery);
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();
                $transactionResult = $stmt->get_result();
                while ($transaction = $transactionResult->fetch_assoc()) {
                    echo "<tr>
                            <td>" . ucfirst(htmlspecialchars($transaction['type'])) . "</td>
                            <td>₹" . number_format($transaction['amount'], 2) . "</td>
                            <td>" . htmlspecialchars($transaction['created_at']) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <form method="POST">
            <button type="submit" name="logout" class="logout-button">Logout</button>
        </form>
    </div>
</body>
</html>
