<?php
session_start();
if (!isset($_SESSION['adminId'])) {
    header("Location: adminLogin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #151922;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: flex-start;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            padding: 10px 30px;
            font-size: 24px;
            font-weight: bold;
            background-color: black;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-sizing: border-box;
        }

        .navigation a {
            text-decoration: none;
            color: white;
            padding: 0 15px;
        }

        .sidebar {
            background-color: black;
            height: 100vh;
            width: 230px;
            color: white;
            padding: 20px;
            position: fixed;
            top: 50px;
            left: 0;
            z-index: 999;
            box-sizing: border-box;
            overflow-y: auto;
        }

        .sidebar .admin-profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .admin-profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        .sidebar .menu {
            display: flex;
            flex-direction: column;
        }

        .sidebar .menu button {
            background-color: #151922;
            color: white;
            border: none;
            font-size: 16px;
            padding: 15px;
            text-align: left;
            width: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .sidebar .menu button i {
            margin-right: 10px;
        }

        .sidebar .menu button:hover {
            background-color: #28231D;
        }
    </style>
</head>
<body>
    <nav class="navigation">
        <span><a href="./adminDashboard.php">Admin Panel</a></span>
        <?php
            $_SESSION['adminId'] ;
            if(isset($_SESSION ['adminId'])){
                echo '<a href="adminProfile.php"> <span>Profile</span></a>';
            }else{
                echo '<a href="adminLogin.php"><span>Logout</span></a>';
            }
        ?>
        
    </nav>

    <div class="sidebar">
        <div class="admin-profile">
            <img src="imgs/phone-banner.jpg" alt="Admin Avatar">
            <?php
                    include 'dbConnect.php';

                    $stmt = $conn->prepare("SELECT * FROM adminsData WHERE adminId = ?");
                    $adId = $_SESSION['adminId'];
                    $stmt->bind_param("s", $adId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $admin = $result->fetch_assoc();
            ?>
            <h3><?php echo $admin['fullName']; ?></h3>
        </div>

        <div class="menu">
            <button onclick="window.location.href='adminDashboard.php'"><i class="fas fa-chart-line"></i> Dashboard</button>
            <button onclick="window.location.href='adminOrders.php'"><i class="fas fa-box"></i> Orders</button>
            <button onclick="window.location.href='adminUsers.php'"><i class="fas fa-users"></i> Users</button>
            <button onclick="window.location.href='adminProduct.php'"><i class="fas fa-box-open"></i> Products</button>
            <button onclick="window.location.href='adminReport.php'"><i class="fas fa-chart-pie"></i> Reports</button>
        </div>
    </div>
</body>
</html>
