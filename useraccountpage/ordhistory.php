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
    die("Error connecting to the database: ". $e->getMessage());
}


$page = isset($_GET['page'])? (int) $_GET['page'] : 1;


$offset = ($page - 1) * 10;


$filter_by = isset($_GET['filter_by'])? $_GET['filter_by'] : '';


$sql = 'SELECT * FROM orders WHERE userID = :userID';
$params = array(':userID' => $_SESSION['userID']);

if ($filter_by == 'date') {
    $sql.= ' ORDER BY orderDate DESC';
} elseif ($filter_by == 'amount') {
    $sql.= ' ORDER BY orderTotalAmount DESC';
} elseif ($filter_by == 'status') {
    $sql.= ' ORDER BY status ASC';
} else {
    $sql.= ' ORDER BY orderDate DESC';
}

$sql.= ' LIMIT :limit OFFSET :offset';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userID', $_SESSION['userID']);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$limit = 10;
$stmt->execute();
$orders = $stmt->fetchAll();


$sql = 'SELECT COUNT(*) AS total_orders FROM orders WHERE userID = :userID';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userID', $_SESSION['userID']);
$stmt->execute();
$total_orders = $stmt->fetch()['total_orders'];


$total_pages = ceil($total_orders / 10);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="wrapper">
    <div class="sidebar">
        <a href="dashboard.php">Dashboard</a>
        <a href="editprofile.php">Edit Profile</a>
        <a href="addressbook.php">Addresses</a>
        <a href="ordhistory.php">Orders</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="content">
        <h2>Orders</h2>
        <p>Your Recent Orders:</p>
        <form action="ordhistory.php" method="get">
            <label for="filter_by">Filter by:</label>
            <select id="filter_by" name="filter_by">
                <option value="">Select an option</option>
                <option value="date">Date</option>
                <option value="amount">Amount</option>
                <option value="status">Status</option>
            </select>
            <button type="submit">Apply Filter</button>
        </form>
        <?php if ($orders):?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Promo Code</th>
                        <th>Status</th>
                        <th>Current Location</th>
                        <th>Last Modified</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order):?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['orderID']);?></td>
                            <td><?php echo htmlspecialchars($order['orderDate']);?></td>
                            <td><?php echo htmlspecialchars($order['orderTotalAmount']);?></td>
                            <td><?php echo htmlspecialchars($order['promoCode']);?></td>
                            <td><?php echo htmlspecialchars($order['status']);?></td>
                            <td><?php echo htmlspecialchars($order['currentLocation']);?></td>
                            <td><?php echo htmlspecialchars($order['orderLastModified']);?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <nav aria-label="...">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++):?>
                        <li class="page-item <?php echo $i == $page? 'active' : '';?>"><a class="page-link" href="ordhistory.php?page=<?php echo $i;?>&filter_by=<?php echo $filter_by;?>&filter_value=<?php echo $filter_value;?>"><?php echo $i;?></a></li>
                    <?php endfor;?>
                </ul>
            </nav>
        <?php else:?>
            <p>No orders found.</p>
        <?php endif;?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
