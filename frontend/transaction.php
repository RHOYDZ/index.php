<?php
session_start();

// Include database configuration
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You are not logged in! Redirecting to login page.'); window.location.href='login.php';</script>";
    exit;
}

// Fetch logged-in user's transactions based on name
$username = $_SESSION['username'];
try {
    $sql = "SELECT name, purpose, payment_method, reference_code, created_at, email, paid, is_reference_code_green
            FROM certificate_indigency 
            WHERE name = (SELECT name FROM users WHERE username = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Page</title>
    <link rel="stylesheet" href="transaction.css">
</head>

<body>
    <div class="sidebar">
        <ul>
            <li><a href="myaccount.php" class="sidebar-link">Home</a></li>
        </ul>
    </div>

    <div class="content">
        <h1 class="content-title">Transactions</h1>
        <p class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Below is the list of your transactions:</p>

        <?php if (count($transactions) > 0): ?>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th class="table-header">Name</th>
                        <th class="table-header">Purpose</th>
                        <th class="table-header">Payment Method</th>
                        <th class="table-header">Reference Code</th>
                        <th class="table-header">Date Created</th>
                        <th class="table-header">Email</th>
                        <th class="table-header">Paid Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $row): ?>
                        <tr class="table-row">
                            <td class="table-data"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($row['purpose']); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($row['payment_method']); ?></td>
                            <td class="table-data <?php echo $row['is_reference_code_green'] == 1 ? 'reference-paid' : ''; ?>">
                                <?php echo htmlspecialchars($row['reference_code']); ?>
                            </td>
                            <td class="table-data"><?php echo date("F j, Y g:i A", strtotime($row['created_at'])); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <span class="paid-status <?php echo $row['paid'] === 'yes' ? 'paid' : 'not-paid'; ?>">
                                    <?php echo $row['paid'] === 'yes' ? 'Paid' : 'Not Paid'; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-transactions-message">No transactions found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
