<?php
include 'dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $adminId = $_POST['adminId'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE adminsData SET fullName=?, email=?, phone=?, password=? WHERE adminId=?");
        $stmt->bind_param("sssss", $fullName, $email, $phone, $hashedPassword, $adminId);
    } else {
        $stmt = $conn->prepare("UPDATE adminsData SET fullName=?, email=?, phone=? WHERE adminId=?");
        $stmt->bind_param("ssss", $fullName, $email, $phone, $adminId);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }
}
?>
