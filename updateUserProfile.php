<?php
include 'dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_POST['userId'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE userData SET fullName=?, email=?,userAddress=?, phone=?, password=? WHERE userId=?");
        $stmt->bind_param("ssssss", $fullName, $email,$address, $phone, $hashedPassword, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE userData SET fullName=?, email=?,userAddress=?, phone=? WHERE userId=?");
        $stmt->bind_param("sssss", $fullName, $email,$address,$phone, $userId);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }
}
?>
