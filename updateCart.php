<?php
session_start();
include 'dbConnect.php';

header('Content-Type: application/json');

// Log incoming request
error_log("Incoming request: " . file_get_contents("php://input"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    // Log received data for debugging
    error_log("Received Data: " . print_r($data, true));

    // Check if userId, productId, and quantity are provided
    if (!isset($data["userId"]) || !isset($data["productId"]) || !isset($data["quantity"])) {
        error_log("Error: Invalid input. Missing userId, productId, or quantity.");
        echo json_encode(["success" => false, "error" => "Invalid input"]);
        exit;
    }

    // Validate session userId matches request userId
    if (!isset($_SESSION["userId"]) || $_SESSION["userId"] != $data["userId"]) {
        error_log("Error: Unauthorized access. Session userId does not match request userId.");
        echo json_encode(["success" => false, "error" => "Unauthorized access"]);
        exit;
    }

    $userId = $data["userId"];
    $productId = $data["productId"];
    $newQuantity = intval($data["quantity"]);

    error_log("Updating userId: $userId, productId: $productId with quantity: $newQuantity");

    if ($newQuantity < 1) {
        echo json_encode(["success" => false, "error" => "Quantity must be at least 1"]);
        exit;
    }

    // Check if the cart item exists
    $stmt = $conn->prepare("SELECT * FROM carts WHERE userId = ? AND productId = ?");
    $stmt->bind_param("ss", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartItem = $result->fetch_assoc();

    if (!$cartItem) {
        echo json_encode(["success" => false, "error" => "Cart item not found"]);
        exit;
    }

    // Fetch unit price from products table
    $stmt = $conn->prepare("SELECT unitPrice FROM products WHERE productId = ?");
    $stmt->bind_param("s", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo json_encode(["success" => false, "error" => "Product not found"]);
        exit;
    }

    $unitPrice = $product['unitPrice'];
    $newTotal = $unitPrice * $newQuantity;

    // Update quantity and total price
    $updateQuery = "UPDATE carts SET quantity = ?, totalPrice = ? WHERE userId = ? AND productId = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("idss", $newQuantity, $newTotal, $userId, $productId);

    if ($stmt->execute()) {
        error_log("Update successful: userId: $userId, productId: $productId, quantity: $newQuantity, totalPrice: $newTotal");
        echo json_encode(["success" => true, "newTotal" => $newTotal]);
    } else {
        error_log("Database update failed: " . $stmt->error);
        echo json_encode(["success" => false, "error" => "Database update failed"]);
    }
    exit;
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit;
}
?>