<?php
session_start();

// Include your database connection file
include_once "db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

// Retrieve updated user data from the database
$userID = $_SESSION['userID'];
$sql = "SELECT username, userFname, userLname FROM users WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($username, $userFname, $userLname);
$stmt->fetch();

// Update session variables
$_SESSION['username'] = $username;
$_SESSION['userFname'] = $userFname;
$_SESSION['userLname'] = $userLname;

$stmt->close();
$conn->close();

// Redirect to the dashboard
header("Location: dashboard.php");
exit();
?>
