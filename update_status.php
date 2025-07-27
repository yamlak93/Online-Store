<?php
include 'dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = $_POST['orderId'];
    $newStatus = $_POST['status'];

    if (empty($orderId) || empty($newStatus)) {
        die("Missing data");
    }

    // Update the order status
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE orderId=?");
    $stmt->bind_param("ss", $newStatus, $orderId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method";
}
?>
