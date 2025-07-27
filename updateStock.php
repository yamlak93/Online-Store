<?php
include 'dbConnect.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json'); 

    // Assign POST data
    $productId = $_POST['productId'] ?? null;
    $status = $_POST['status'] ?? null;

    // Validate input
    if (empty($productId) || empty($status)) {
        echo json_encode(["success" => false, "error" => "Missing product ID or status."]);
        exit();
    }

    // Prepare the SQL query to update the status
    $stmt = $conn->prepare("UPDATE products SET status = ? WHERE productId = ?");
    $stmt->bind_param("ss", $status, $productId);

    // Execute query
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}

?>
