<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .dashboard{
            position: absolute;
            top: 50px;
            left: 270px;
            color: white;
            width: calc(100% - 290px);
        }

        .hero{
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            width: 958px;
            height: 100px;
            text-align: center;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
        }

        .stats-container{
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .stat-box{
            background-color: #1e242e;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 180px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        }

        .stat-box h3{
            margin: 10px 0;
            font-size: 20px;
        }

        .stat-box p{
            font-size: 24px;
            font-weight: bold;
        }

        .admin-actions{
            margin-top: 30px;
        }

        .admin-actions h2{
            text-align: center;
        }

        .action-list{
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-card{
            background-color: #1e242e;
            padding: 15px;
            margin: 10px;
            border-radius: 8px;
            width: 200px;
            text-align: center;
            cursor: pointer;
        }

        .action-card:hover{
            transform: scale(1.08);
            background-color: #ff4b2b;
        }
    </style>
</head>
<body>
    <?php
        include 'adminNav.php';
    ?>
    <div class="dashboard">
        <div class="hero">
            <h1>Admin Dashboard</h1>
        </div>

        <div class="stats-container">
            <div class="stat-box">
            <?php
                include 'dbConnect.php';
                $sql = "SELECT SUM(totalPrice) AS totalSales FROM orders";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $totalSales = $row['totalSales']??0;

            ?>
                <h3>Total Sales</h3>
                <p>$<?php echo $totalSales;?></p>
            </div>
            <div class="stat-box">
            <?php
                include 'dbConnect.php';
                $sql = "SELECT COUNT(*) AS totalOrders FROM orders";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $totalOrders = $row['totalOrders'];

            ?>
                <h3>Total Orders</h3>
                <p><?php echo $totalOrders;?></p>
            </div>
            <?php
                include 'dbConnect.php';
                $sql = "SELECT COUNT(*) AS totalUsers FROM userData";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $totalUsers = $row['totalUsers'];

            ?>
            <div class="stat-box">
                <h3>Registered users</h3>
                <p><?php echo $totalUsers;?></p>
            </div>
            <?php
            $sql = "SELECT COUNT(*) AS totalProducts FROM products";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $totalProducts = $row['totalProducts'];
            $conn->close();
            ?>
            <div class="stat-box">
                <h3>Products Avaliable</h3>
                <p><?php echo "$totalProducts"; ?></p>
            </div>
        </div>
        <section class="admin-actions">
            <h2>Quick Actions</h2>
            <div class="action-list">
                <div class="action-card" onclick="window.location.href='adminOrders.php'">
                    <h3>Manage Orders</h3>
                </div>
                <div class="action-card" onclick="window.location.href='adminUsers.php'">
                    <h3>Manage Users</h3>
                </div>
                <div class="action-card" onclick="window.location.href='adminProduct.php'">
                    <h3>Manage Products</h3>
                </div>
                <div class="action-card" onclick="window.location.href='adminReport.php'">
                    <h3>View Reports</h3>
                </div>
            </div>
        </section>
    </div>
</body>
</html>