<?php
include 'dbConnect.php';

if($_SERVER['REQUEST_METHOD'] === "POST")
{
    session_start();

    $productId = $_POST['productId'];
    $productQuantity = $_POST['productQuantity'];
    $productName = $_POST['productName'];
    $totalPrice = $_POST['totalPrice'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $paymentOpt = $_POST['paymentOpt'];
    $account = $_POST['account'];
    $userId = $_SESSION['userId'];
    $userName = $_SESSION['fullName'];
    $status = "pending";

    do {
        $orderId = uniqid('ORD_');
        $checkOrdId = $conn->prepare("SELECT orderId FROM orders WHERE orderId=?");  
        if (!$checkOrdId) {
            die("Prepare failed: " . $conn->error);
        }
        $checkOrdId->bind_param("s", $orderId);
        $checkOrdId->execute();
        $idExists = $checkOrdId->get_result()->num_rows > 0;
    } while ($idExists);

    $stmt = $conn->prepare("INSERT INTO orders (orderId, userId, userName, productsName, totalPrice, phone, shippingAddress, paymentOption, accountNumber, status)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error); 
    }

    $stmt->bind_param("ssssdissss", $orderId, $userId, $userName, $productName, $totalPrice, $phone, $address, $paymentOpt, $account, $status);

    if ($stmt->execute()) {
        $productNames = explode(", ", $_POST['productName']); 
        $prdIds = explode(", ", $_POST['productId']); 
        $productQuantities = explode(", ", $_POST['productQuantity']); 

        // Prepare insert statement for sales table
        $stmt_sales = $conn->prepare("INSERT INTO sales (productId, productName, unitPrice, soldQuantity, totalPrice) VALUES (?, ?, ?, ?, ?)");

        for ($i = 0; $i < count($prdIds); $i++) {
            $productId = $prdIds[$i];  // Ensure productId is an integer
            $productName = $productNames[$i];
            $soldQuantity = (int)$productQuantities[$i];  // Ensure quantity is an integer

            // Fetch unit price from products table
            $stmt_product = $conn->prepare("SELECT unitPrice FROM products WHERE productId = ?");
            $stmt_product->bind_param("s", $productId);
            $stmt_product->execute();
            $result = $stmt_product->get_result();

            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                $unitPrice = $product['unitPrice'];
                $totalProductPrice = $unitPrice * $soldQuantity;

                // Insert into sales table
                $stmt_sales->bind_param("ssdid", $productId, $productName, $unitPrice, $soldQuantity, $totalProductPrice);
                $stmt_sales->execute();
            }
        }

       echo '<script> alert("Order Placed Successfully!");
                window.location.href="cart.php";
        </script>';
        $clearCart = $conn->prepare("DELETE FROM carts WHERE userId=?");
        $clearCart->bind_param("s", $userId);
        $clearCart->execute();
    } else {
        echo '<script> alert("Order did not placed: ' . $stmt->error . '");
                 window.location.href="cart.php";        
              </script>';
    }
    
    $stmt->close();
    $checkOrdId->close();
    $conn->close();
}
?>
