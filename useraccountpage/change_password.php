<!-- change_password.php -->
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

// Include your database connection file
include_once "db_connection.php"; // Adjust this as per your actual file name and location

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_SESSION['userID'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    // Validate form data (you may add more validation as needed)
    if (empty($currentPassword) || empty($newPassword)) {
        $_SESSION['error_message'] = "Please fill in all fields.";
        header("Location: change_password.php");
        exit();
    }

    // Retrieve current password hash from the database
    $sql = "SELECT password FROM users WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($storedPassword);
    $stmt->fetch();
    $stmt->close();

    // Verify current password
    if (password_verify($currentPassword, $storedPassword)) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update the password in the database
        $sqlUpdate = "UPDATE users SET password = ? WHERE userID = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("si", $hashedPassword, $userID);
        
        if ($stmtUpdate->execute()) {
            $_SESSION['success_message'] = "Password updated successfully.";
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error updating password. Please try again later.";
            header("Location: change_password.php");
            exit();
        }
        
        $stmtUpdate->close();
    } else {
        $_SESSION['error_message'] = "Current password is incorrect.";
        header("Location: change_password.php");
        exit();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
    .content {
        border: 1px solid blue;
        margin: 40px 200px;
    }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content">
            <a class="to-dashboard" href="dashboard.php">Back to Dashboard</a>
            <h2>Change Password</h2>
            <?php
            if (isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']);
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
