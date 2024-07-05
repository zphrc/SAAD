<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT userID, userFname, userLname, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userID, $userFname, $userLname, $hashed_password);

    if ($stmt->num_rows == 1 && $stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['userID'] = $userID;
        $_SESSION['userFname'] = $userFname;
        $_SESSION['userLname'] = $userLname;
        header("Location: dashboard.php");
    } else {
        echo "Invalid email or password";
    }

    $stmt->close();
    $conn->close();
}
?>