<?php
include 'dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['productId'])) {
        echo json_encode(["success" => false, "error" => "Product ID is missing"]);
        exit;
    }

    $productId = $_POST['productId'];

    // Prepare DELETE statement
    $stmt = $conn->prepare("DELETE FROM products WHERE productId = ?");
    $stmt->bind_param("s", $productId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to delete product"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
}
?>
