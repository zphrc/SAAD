<?php
// session_start();
// if (!isset($_SESSION['user_id'])) {
//     header("Location: index.php");
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <!--<a class="navbar-brand mx-auto" href="dashboard.php">
                <img src="img\GESTOURAGE.png" alt="Logo" height="40">
            </a>-->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link profile-icon" href="index.html">
                        <img src="img\profileicon.png" alt="Profile" width="30" height="30">
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Welcome to Your Dashboard!</h4>
            <p>Hello, <?php echo isset($_SESSION['userFname']) ? $_SESSION['userFname'] : 'Guest'; ?> <?php echo isset($_SESSION['userLname']) ? $_SESSION['userLname'] : ''; ?>! We hope you're having a great day.</p>
            <hr>
            <p class="mb-0">Click the icon above to login</p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
