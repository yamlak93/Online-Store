<?php
include 'dbConnect.php';
if($_SERVER['REQUEST_METHOD'] === "POST")
{
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $conPassword = $_POST['conPassword'];

    if ($password !== $conPassword) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    } 

    $checkstmt = $conn->prepare("SELECT * FROM adminsData WHERE email =? AND phone=?");
    $checkstmt->bind_param("ss", $email, $phone);
    $checkstmt->execute();
    $result = $checkstmt->get_result();
   

    if($result->num_rows > 0)
    {
        echo "<script>alert('User already exists!'); window.history.back(); </script>";
    }else{
        do{
            $adminId = uniqid('ADM_');
            $checkAdminId = $conn->prepare("SELECT adminId FROM adminsData WHERE adminId = ?");
            $checkAdminId->bind_param("s", $adminId);
            $checkAdminId->execute();
            $idExists = $checkAdminId->get_result()->num_rows > 0;
        }while($idExists);

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $conn->begin_transaction();
        try{
            $stmt = $conn->prepare("INSERT INTO adminsData (adminId, fullName, email, phone, password)
                                    VALUES(?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $adminId, $fullName, $email, $phone, $hashedPassword);
            if($stmt->execute()){
                session_start();
                $_SESSION['adminId'] = $admin['adminId'];
                $_SESSION['fullName'] = $admin['fullName'];
                
                $conn->commit();
                header("Location: adminDashboard.php?signUp=success");
                exit;
            }else{
                throw new Exception("Error executing query: " . $stmt->error);
            }
        }catch(Exception $e){
            $conn->rollback();
            echo "<script>alert('Error in registering user: " . $e->getMessage() . "');</script>";
        }
    }
}
?>