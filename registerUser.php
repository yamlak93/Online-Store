<?php
include 'dbConnect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $conPassword = $_POST['conPassword'];

    // Check if email or phone already exists
    $checkStmt = $conn->prepare("SELECT * FROM userData WHERE email = ? OR phone = ?");
    $checkStmt->bind_param("ss", $email, $phone);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($password !== $conPassword) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }    

    if ($result->num_rows > 0) {
        echo "<script>alert('User already exists!');</script>";
        echo"<script>window.location.href='userLogin.php' </script>";
        
    } else {
        // Generate unique userId
        do {
            $userId = uniqid('USR_'); // Example: USR_6543abc123
            $checkUserId = $conn->prepare("SELECT userId FROM userData WHERE userId = ?");
            $checkUserId->bind_param("s", $userId);
            $checkUserId->execute();
            $idExists = $checkUserId->get_result()->num_rows > 0;
        } while ($idExists); // Keep generating until unique


        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into database
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO userData (userId, fullName, email, phone, userAddress, password) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $userId, $fullName, $email, $phone, $address, $hashedPassword);

            if ($stmt->execute()) {
                $_SESSION['userId'] = $userId;
                $_SESSION['fullName'] = $fullName;
                $conn->commit();
                header("Location: dashboard.php?signup=success");
                exit;
            } else {
                throw new Exception("Error executing query: " . $stmt->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Error in registering user: " . $e->getMessage() . "');</script>";
        }
    }
}
?>
