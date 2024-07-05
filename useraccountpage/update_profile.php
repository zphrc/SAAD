<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

// Include your database connection file
include_once "db_connection.php"; // Adjust this as per your actual file name and location

// Retrieve form data
$username = $_POST['username'];
$userFname = $_POST['userFname'];
$userLname = $_POST['userLname'];

// Basic validation
if (empty($username) || empty($userFname) || empty($userLname)) {
    // Handle empty fields scenario
    $_SESSION['error_message'] = "All fields are required.";
    header("Location: editprofile.php");
    exit();
}

// Sanitize input (optional but recommended)
$username = htmlspecialchars($username);
$userFname = htmlspecialchars($userFname);
$userLname = htmlspecialchars($userLname);

// Prepare and execute SQL update query
$sql = "UPDATE users SET username = ?, userFname = ?, userLname = ? WHERE userID = ?";
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("sssi", $username, $userFname, $userLname, $_SESSION['userID']);

// Execute the statement
if ($stmt->execute()) {
    // Update successful, reload session variables
    $_SESSION['success_message'] = "Profile updated successfully.";
    header("Location: reload_session.php");
    exit();
} else {
    // Handle database errors
    $_SESSION['error_message'] = "Error updating profile. Please try again later.";
    header("Location: editprofile.php");
    exit();
}

$stmt->close();
$conn->close();
?>
