<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userFname = $_POST['first_name'];
    $userLname = $_POST['last_name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, userFname, userLname) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $userFname, $userLname);
    if ($stmt->execute()) {
        echo "Registration successful";
        header("Location: index.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
