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

    <div class="wrapper">
        <div class="sidebar">
            <a href="dashboard.php">My Dashboard</a>
            <a href="editprofile.php">Edit Profile</a>
            <a href="ordhistory.php">My Orders</a><hr>
            <a href="logout.php">Logout</a>
        </div>
        <div class="content">
            <h2>Dashboard</h2>
            <p>Welcome, <?php echo $_SESSION['userFname']; ?> <?php echo $_SESSION['userLname']; ?>!</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
