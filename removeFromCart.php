<?php
session_start();
include 'dbConnect.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["cartId"])) {
        echo json_encode(["success" => false, "error" => "Invalid input"]);
        exit;
    }

    $cartId = $data["cartId"];

    // Delete the cart item
    $stmt = $conn->prepare("DELETE FROM carts WHERE productId = ?");
    $stmt->bind_param("s", $cartId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Database error"]);
    }
    exit;
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit;
}
?>