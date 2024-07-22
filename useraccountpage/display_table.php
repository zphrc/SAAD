<?php
session_start();
include "db_connection.php";

$sql = "SELECT orderDate, orderTotalAmount, promoCode, status, currentLocation FROM orders";
$result = $conn->query($sql);

// Array to hold rows of data
$rows = array();

// Fetching rows of data from MySQL
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

// Close connection
$conn->close();

// Sending JSON response back to frontend
header('Content-Type: application/json');
echo json_encode($rows);
?>