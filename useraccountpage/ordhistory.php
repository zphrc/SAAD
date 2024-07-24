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
$limit = 5; // Number of records per page
$offset = ($page - 1) * $limit;

$filter_by = isset($_GET['filter_by'])? $_GET['filter_by'] : '';

// Construct SQL query with filtering and pagination
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

// Add pagination to SQL query
$sql.= ' LIMIT :limit OFFSET :offset';

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userID', $_SESSION['userID']);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll();

// Count total number of orders
$sqlCount = 'SELECT COUNT(*) AS total_orders FROM orders WHERE userID = :userID';
$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->bindParam(':userID', $_SESSION['userID']);
$stmtCount->execute();
$total_orders = $stmtCount->fetchColumn();

// Calculate total pages
$total_pages = ceil($total_orders / $limit);
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

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar for desktop -->
        <div class="col-md-3 d-none d-md-block sidebar-container">
            <a href="dashboard.php">Dashboard</a>
            <a href="editprofile.php">Edit Profile</a>
            <a href="address.php">Addresses</a>
            <a href="ordhistory.php">Orders</a>
            <hr>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Sidebar toggle button for mobile -->
        <button class="btn btn-primary d-block d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">Menu</button>

        <!-- Content -->
        <div class="col-md-9 col-12 content table-responsive">
            <h2>Orders</h2>
            <form action="ordhistory.php" method="get">
                <label for="filter_by">Filter by:</label>
                <select id="filter_by" name="filter_by" class="form-select mb-3">
                    <option value="">Select an option</option>
                    <option value="date">Date</option>
                    <option value="amount">Amount</option>
                    <option value="status">Status</option>
                </select>
                <button type="submit" class="btn btn-primary">Apply Filter</button>
            </form>
            <?php if ($orders):?>
                <table class="table table-bordered table-hover mt-3">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order):?>
                            <tr class="order-row" data-order-details='<?php echo json_encode($order); ?>'>
                                <td><?php echo htmlspecialchars($order['orderID']);?></td>
                                <td><?php echo htmlspecialchars($order['orderDate']);?></td>
                                <td><?php echo htmlspecialchars($order['orderTotalAmount']);?></td>
                                <td><?php echo htmlspecialchars($order['status']);?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <nav aria-label="...">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++):?>
                            <li class="page-item <?php echo $i == $page? 'active' : '';?>">
                                <a class="page-link" href="ordhistory.php?page=<?php echo $i;?>&filter_by=<?php echo $filter_by;?>">
                                    <?php echo $i;?>
                                </a>
                            </li>
                        <?php endfor;?>
                    </ul>
                </nav>
            <?php else:?>
                <p>No orders found.</p>
            <?php endif;?>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <a href="dashboard.php">Dashboard</a>
        <a href="editprofile.php">Edit Profile</a>
        <a href="address.php">Addresses</a>
        <a href="ordhistory.php">Orders</a>
        <hr>
        <a href="logout.php">Logout</a>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="orderDetails">
                    <!-- Order details will be dynamically inserted here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // JavaScript to handle showing order details in modal
    document.addEventListener('DOMContentLoaded', function() {
        const orderRows = document.querySelectorAll('.order-row');
        const orderModal = new bootstrap.Modal(document.getElementById('orderModal'));

        orderRows.forEach(row => {
            row.addEventListener('click', function() {
                const orderDetails = JSON.parse(this.getAttribute('data-order-details'));
                populateOrderModal(orderDetails);
                orderModal.show();
            });
        });

        function populateOrderModal(order) {
            const modalBody = document.getElementById('orderDetails');
            modalBody.innerHTML = `
                <p><strong>Order ID:</strong> ${order.orderID}</p>
                <p><strong>Order Date:</strong> ${order.orderDate}</p>
                <p><strong>Total Amount:</strong> ${order.orderTotalAmount}</p>
                <p><strong>Promo Code:</strong> ${order.promoCode}</p>
                <p><strong>Status:</strong> ${order.status}</p>
                <p><strong>Current Location:</strong> ${order.currentLocation}</p>
                <p><strong>Last Modified:</strong> ${order.orderLastModified}</p>
            `;
        }
    });
</script>


</body>
</html>
