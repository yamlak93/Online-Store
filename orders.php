<?php
    session_start();
    if (!isset($_SESSION['userId'])) {
        header("Location: dashboard.php");
        exit();
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <style>


        .orders-container {
            position: absolute;
            top: 50px;
            left: 270px;
            color: white;
            right: 30px;
        }

        .hero {
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            width: 958px;
            height: 70px;
            text-align: center;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 5px;
            overflow: hidden;
        }
        .orders-table th, .orders-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .orders-table th{
            background-color: #ff4b2b;
        }

        .orders-table img {
            width: 60px;
            height: 60px;
            border-radius: 5px;
        }

        .status {
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 5px;
        }
        .pending {
            background-color: orange;
            color: white;
        }
        .delivered {
            background-color: green;
            color: white;
        }
        .cancelled {
            background-color: red;
            color: white;
        }
        .shipped {
            background-color: blue;
            color: white;
        }
        @media (max-width: 768px) {
        .orders-container {
            position: absolute;
                left: 5px;
                right: 5px;
                padding: 10px;
                margin: 0 auto;
                width: 100%;
                max-width: 600px;
        }

        .hero {
            width: 100%;
            height: auto;
            padding: 20px;
            margin-top: 10px;
        }

        table {
            width: 500px;
            display: block;
            overflow-x: auto;
        }

        .orders-table th, .orders-table td {
            padding: 8px;
            font-size: 14px;
        }

        .orders-table img {
            width: 50px;
            height: 50px;
        }
    }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <div class="orders-container">
        <div class="hero"><h1>Your Orders</h1></div>
        <div class="orders-content">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Customer Address</th>
                        <th>Customer Phone</th>
                        <th>Products</th>
                        <th>Price</th>
                        <th>Payment Option</th>
                        <th>Account Number</th>
                        <th>Status</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'dbConnect.php';
                    $userId = $_SESSION['userId'];
                    $fullName = $_SESSION['fullName'];
                    $stmt = $conn->prepare("SELECT * FROM orders WHERE userId=?");
                    $stmt->bind_param("s", $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows > 0)
                    {
                        $rows = [];
                        while ($row = $result->fetch_assoc()) {
                            $rows[] = $row;  // Store each row in an array
                        }

                        // Loop through the array in reverse order
                        foreach (array_reverse($rows) as $row) {
                            $sta = $row['status'];
                            echo '<tr>
                                    <td>'.$row['orderId'].'</td>
                                    <td>'.$row['userName'].'</td>
                                    <td>'.$row['shippingAddress'].'</td>
                                    <td>'.$row['phone'].'</td>
                                    <td>'.$row['productsName'].'</td>
                                    <td>'.$row['totalPrice'].'</td>
                                    <td>'.$row['paymentOption'].'</td>
                                    <td>'.$row['accountNumber'].'</td>
                                    <td><span class="status '.$sta.'">'. $sta .'</span></td>
                                    <td>'.$row['orderDate'].'</td>
                                </tr>';
                        }
                    }else{
                        echo "<tr><td colspan='9' style='text-align: center;'>You don't have any order!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
