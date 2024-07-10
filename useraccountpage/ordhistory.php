<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

$host = 'localhost';
$db = 'gestoni';
$user = 'root';  
$pass = '';  


$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}


$sql = 'SELECT * FROM orders WHERE userID = :userID';
$stmt = $pdo->prepare($sql);
$stmt->execute(['userID' => $_SESSION['userID']]);
$orders = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .content {
            height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="sidebar">
        <a href="dashboard.php">My Dashboard</a>
        <a href="editprofile.php">Edit Profile</a>
        <a href="ordhistory.php">My Orders</a><hr>
        <a href="logout.php">Logout</a>
    </div>
    <div class="content">
        <h2>Orders</h2>
        <p>Your Recent Orders:</p>
        <?php if ($orders): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Promo Code</th>
                        <th>Status</th>
                        <th>Current Location</th>
                        <th>Last Modified</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['orderDate']); ?></td>
                            <td><?php echo htmlspecialchars($order['orderTotalAmount']); ?></td>
                            <td><?php echo htmlspecialchars($order['promoCode']); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td><?php echo htmlspecialchars($order['currentLocation']); ?></td>
                            <td><?php echo htmlspecialchars($order['orderLastModified']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
