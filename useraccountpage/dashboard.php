<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
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
            <h2>Dashboard</h2>
            <p>Welcome, <?php echo $_SESSION['userFname']; ?> <?php echo $_SESSION['userLname']; ?>!</p>
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
