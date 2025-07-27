<?php
session_start(); // Start session at the top
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json"); 
include 'dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate required fields
    $requiredFields = ["productId", "productName", "productImg", "unitPrice", "quantity", "category", "subCategory"];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            echo json_encode(["success" => false, "error" => "Missing or empty field: $field"]);
            exit;
        }
    }

    // Get data
    $productId = $_POST["productId"];  
    $productName = $_POST["productName"];
    $productImg = $_POST["productImg"];
    $unitPrice = floatval($_POST["unitPrice"]);
    $quantity = intval($_POST["quantity"]);
    $category = $_POST["category"];
    $subCategory = $_POST["subCategory"];
    $userId = $_SESSION['userId'];
    $totalPrice = $unitPrice * $quantity;

    // Check if product exists in cart
    $checkQuery = "SELECT * FROM carts WHERE userId = ? AND productId = ? AND category = ? AND subCategory = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ssss", $userId, $productId, $category, $subCategory);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity and total price
        $updateQuery = "UPDATE carts 
            SET quantity = quantity + ?, totalPrice = (unitPrice * (quantity + ?)) 
            WHERE userId = ? AND productId = ? AND category = ? AND subCategory = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iissss", $quantity, $quantity, $userId, $productId, $category, $subCategory);
    } else {
        // Insert new product

        $insertQuery = "INSERT INTO carts (userId, productId, productImg, productName, unitPrice, quantity, totalPrice, category, subCategory) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssssdiiss", $userId, $productId, $productImg, $productName, $unitPrice, $quantity, $totalPrice, $category, $subCategory);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Product added to cart"]);
    } else {
        echo json_encode(["success" => false, "error" => "SQL Error: " . $stmt->error]);
    }

    exit;
}
?>
