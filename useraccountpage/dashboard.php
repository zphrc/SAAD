<?php
// session_start();
// if (!isset($_SESSION['user_id'])) {
//     header("Location: dashboard.php");
//     exit();
// }
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
            <a href="dashboard.php">Dashboard</a>
            <a href="editprofile.php">Edit Profile</a>
            <a href="appointments.php">Appointments</a>
            <a href="settings.php">Settings</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="content">
            <h2>Dashboard</h2>
            <p>Welcome, Jane<?php //echo $_SESSION['first_name']; ?>!</p>
            <!-- Add your dashboard content here -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>