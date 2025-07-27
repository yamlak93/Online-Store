<?php
include 'dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json");

    if (!isset($_POST['userId'])) {
        echo json_encode(["success" => false, "error" => "User ID is missing"]);
        exit;
    }

    $userId = $_POST['userId'];

    $stmt = $conn->prepare("DELETE FROM userData WHERE userId = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "User not found or already deleted"]);
    }

    $stmt->close();
    $conn->close();
}
?>
