<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

// Include your database connection file
include_once "db_connection.php"; // Adjust this as per your actual file name and location

// Retrieve current user details from the database
$userID = $_SESSION['userID'];
$sql = "SELECT username, userFname, userLname FROM users WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($currentUsername, $currentUserFname, $currentUserLname);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
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
            <a class="to-dashboard" href="dashboard.php">Back to Dashboard</a>
            <h2>Edit Profile</h2>
            <form action="update_profile.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($currentUsername); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="userFname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="userFname" name="userFname" value="<?php echo htmlspecialchars($currentUserFname); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="userLname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="userLname" name="userLname" value="<?php echo htmlspecialchars($currentUserLname); ?>" required>
                </div>
                <!-- <a href="change_password.php" class="btn btn-secondary">Change Password</a> -->
                <button type="submit" class="btn btn-primary custom">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
