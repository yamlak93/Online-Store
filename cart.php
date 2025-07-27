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
    <title>Cart</title>
    <style>
            .cart {
            position: absolute;
            top: 50px;
            left: 270px;
            right: 30px;
            color: white;
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

        .hero button {
            float: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .cart-table th, .cart-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .cart-table img {
            width: 60px;
            height: 60px;
            border-radius: 5px;
        }

        .quantity-controls button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            background-color: #ff4b2b;
            color: white;
            border-radius: 3px;
            user-select: none
        }

        .remove-btn {
            background-color: red;
            color: white;
            padding: 6px 12px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        .checkout-btn {
            background-color: green;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            display: block;
            margin: 20px auto;
            width: 200px;
            border-radius: 5px;
        }

        .checkout-btn:hover{
            background-color: lime;
        }

        .cart-summary{
            text-align: center;
            align-items: center;
        }

        .summary-table{
            color: white;
            padding: 12px 20px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
            width: 600px;
            border-radius: 5px;
        }
        .summary-table th, .summary-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 20px;
        }

        .cart-table th{
            background-color: #ff4b2b;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s ease-in-out;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 12px;
            width: 400px;
            color: black;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-20px);
            animation: slideDown 0.3s ease-in-out forwards;
            max-height: 80vh; /* Set maximum height */
            overflow-y: auto; /* Enable vertical scroll */
            margin-top: 50px;
        }

        .modal-content::-webkit-scrollbar {
            width: 6px;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 6px;
        }

        .modal-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close-btn {
            color: red;
            font-size: 22px;
            font-weight: bold;
            float: right;
            cursor: pointer;
            transition: color 0.2s;
        }

        .close-btn:hover {
            color: darkred;
        }

        .checkout-table {
            color: black;
            margin: 20px auto;
            width: 100%;
            border-collapse: collapse;
            font-size: 18px;
        }

        .checkout-table th, .checkout-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid black;
        }

        .checkout {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .checkout label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            outline: none;
        }

        .orderBtn {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .orderBtn:hover {
            background-color: #218838;
        }
        @media (max-width: 768px) {
        .cart {
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

        .cart-table th, .cart-table td {
            padding: 8px;
            font-size: 14px;
        }

        .cart-table img {
            width: 50px;
            height: 50px;
        }

        .quantity-controls button {
            padding: 5px;
        }

        .checkout-btn {
            width: 300px;;
            padding: 10px;
            font-size: 14px;
        }

        .summary-table {
            width: 400px;
            padding: 10px;
        }

        .summary-table th, .summary-table td {
            padding: 8px;
            font-size: 14px;
        }

        .modal-content {
            width: 90%;
            padding: 20px;
        }

        .checkout-table {
            font-size: 14px;
        }

        .checkout-table th, .checkout-table td {
            padding: 8px;
        }

        .checkout label {
            font-size: 14px;
        }

        input, select {
            padding: 8px;
            font-size: 14px;
        }

        .orderBtn {
            padding: 10px;
            font-size: 16px;
        }
    }


    </style>
</head>
<body>
    <?php
     include 'navigation.php'; ?>
    <div class="cart">
        <div class="hero"><h1>Your shopping cart</h1></div>
        <div class="cart-content">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Remove</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    include 'dbConnect.php';
                    $userId = $_SESSION['userId'];
                    $fullName = $_SESSION['fullName'];
                    $stmt = $conn->prepare("SELECT carts.*, products.productImg FROM carts JOIN products ON carts.productId = products.productId WHERE carts.userId=?");
                    $stmt->bind_param("s", $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $imageData = base64_encode($row['productImg']);
                            $imageSrc = 'data:image/jpeg;base64,' . $imageData;

                            echo '<tr data-userid="'.$userId.'" data-productid="'.$row['productId'].'">
                                <td><img src="' . $imageSrc . '" alt="cart_img" width="100" height="100"></td>
                                <td>' . $row['productName'] . '</td>
                                <td>' . $row['category'] . '</td>
                                <td>' . $row['subCategory'] . '</td>
                                <td class="price">$' . number_format($row['unitPrice'], 2) . '</td>
                                <td class="quantity-controls">
                                    <button class="decrease">-</button> 
                                    <span class="quantity">' . $row['quantity'] . '</span> 
                                    <button class="increase">+</button>
                                </td>
                                <td class="total-price"><strong>$' . number_format($row['totalPrice'], 2) . '</strong></td>
                                <td><button class="remove-btn" data-cartid="' . $row['productId'] . '">Remove</button></td>
                            </tr>';
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align: center;'>Your cart is empty!</td></tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
        <div class="cart-summary">
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>Total (Before tax)</th>
                        <th>Tax 15%</th>
                        <th>Shipment fee 2%</th>
                        <th>Total </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Fetch the total sum of all products for the specific user
                        $totStmt = $conn->prepare("SELECT SUM(totalPrice) AS totalSum FROM carts WHERE userId=?");
                        $totStmt->bind_param("s", $userId);
                        $totStmt->execute();
                        $result = $totStmt->get_result();
                        $sum = $result->fetch_assoc();

                        // Calculate tax (15%) and grand total
                        $subtotal = $sum['totalSum'] ?? 0; // Use null coalescing operator to handle null values
                        $tax = $subtotal * 0.15;
                        $shipment = $subtotal * 0.02;
                        $grandTotal = $subtotal + $tax + $shipment;
                        ?>

                        <tr>
                            <td><?php echo '$' . number_format($subtotal, 2); ?></td>
                            <td><?php echo '$' . number_format($tax, 2); ?></td>
                            <td><?php echo '$' . number_format($shipment, 2); ?></td>
                            <td><?php echo '$' . number_format($grandTotal, 2); ?></td>
                        </tr>
                </tbody>
            </table>
            <?php
                $sql = "SELECT COUNT(*) AS totalCart FROM carts WHERE userId=?";
                $contStmt = $conn->prepare($sql);

                if (!$contStmt) {
                    die("Prepare failed: " . $conn->error); // Debugging
                }

                $contStmt->bind_param("s", $userId);
                $contStmt->execute(); // Missing execution

                $result = $contStmt->get_result();
                $row = $result->fetch_assoc();
                $totalCart = $row['totalCart'] ?? 0; // Prevent errors if row is null

                if ($totalCart > 0) {
                    echo '<button class="checkout-btn" onclick="openModal(\'checkoutModal\')">Proceed to Checkout</button>';
                } else {
                    echo '<button class="checkout-btn" style="display: none;" onclick="openModal(\'checkoutModal\')">Proceed to Checkout</button>';
                }
                ?>

          
        </div>
        <div class="modal" id="checkoutModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('checkoutModal')">&times</span>
            <h1 style="text-align: center">Checkout</h1>
            <div class="orderDetail">
                <table class="checkout-table">
                    <thead>
                        <th>Number of Producs</th>
                        <th>Total Price</th>
                    </thead>
                    <tbody>
                        <?php
                        $countStmt = $conn->prepare("SELECT COUNT(productId) AS numberOfProducts FROM carts WHERE userId=?");
                        $countStmt->bind_param("s", $userId);
                        $countStmt->execute();
                        $num = $countStmt->get_result();
                        $resNum = $num->fetch_assoc();
                        $numOfPro = $resNum['numberOfProducts'] ?? 0;
                        ?>
                        <tr>
                            <td><?php echo $numOfPro;  ?></td>
                            <td><?php echo '$ '. $grandTotal; ?></td>
                        </tr>
                        <tr ><td colspan="2" style="text-align: center;"> Price includes Tax and shipping fees.</td></tr>
                    </tbody>
                </table>
            </div>
            <?php
                $nameStmt = $conn->prepare("SELECT productId, productName, quantity FROM carts WHERE userId=?");
                $nameStmt->bind_param("s", $userId);
                $nameStmt->execute();
                $resultName = $nameStmt->get_result();

                $names = ""; 
                $prdId = "";
                $prdQuantity = "";

                while ($rowName = $resultName->fetch_assoc()) {
                    $names .= $rowName['productName'] . ", "; 
                    $prdId .= $rowName['productId'] . ", ";
                    $prdQuantity .= $rowName['quantity'] . ", ";
                }
                $names = rtrim($names, ", ");
                $prdId = rtrim($prdId, ", ");
                $prdQuantity = rtrim($prdQuantity, ", ");
            ?>

            <form action="addToOrder.php" method="POST" class="checkout">
                <input type="hidden" name="productId" value="<?php echo $prdId; ?>">
                <input type="hidden" name="productName" value="<?php echo $names; ?>">
                <input type="hidden" name="productQuantity" value="<?php echo $prdQuantity; ?>">
                <input type="hidden" name="totalPrice" value="<?php echo $grandTotal?>">
                <label for="phone">Phone Number</label>
                <input type="number" id="phone" name="phone" required>
                <label for="address">Shipping Address</label>
                <input type="address" name="address" id="address" required>
                <label for="">Payment Option</label>
                <select name="paymentOpt" id="paymentOpt" required>
                    <option value="">Select Payment Option</option>
                    <option value="telebirr">Telebirr</option>
                    <option value="cbe">CBE</option>
                    <option value="awash">Awash</option>
                    <option value="abyssinia">Abyssinia</option>
                </select>
                <label for="account">Account number</label>
                <input type="number" name="account" id="account" required>
                <span style="display: flex;"> <label for="confirm">Confirm Order</label>
               <input type="checkbox" name="confirm" id="confirm" value="confirm" onclick="checkBox('orderBtn')" required></span> 
                <button type="submit" class="orderBtn" id="orderBtn" style="display: none">Order Now</button>
            </form>
        </div>
    </div>



    <script>
            // Function to update quantity in the database and UI
    function updateQuantityInDatabase(quantity, userId, productId, row) {
    const price = parseFloat(row.querySelector('.price').textContent.replace('$', '')); // Get unit price
    const totalPriceCell = row.querySelector('.total-price'); // Total price cell

    const requestData = {
        userId: userId,
        productId: productId,
        quantity: quantity
    };

    fetch("updateCart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the total price in the UI using the response from the server
            const newTotal = data.newTotal; // Use the newTotal returned from the server
            totalPriceCell.textContent = `$${newTotal.toFixed(2)}`;

            // Update the cart summary
            updateSummary();
        } else {
            alert("Error updating quantity: " + data.error);
        }
    })
    .catch(error => console.error("Error:", error));
}



        // Add event listeners to quantity controls
        document.querySelectorAll('.cart-table tbody tr').forEach(row => {
            const decreaseBtn = row.querySelector('.decrease');
            const increaseBtn = row.querySelector('.increase');
            const quantitySpan = row.querySelector('.quantity');
            const userId = row.getAttribute('data-userid');
            const productId = row.getAttribute('data-productid');

            decreaseBtn.addEventListener('click', () => {
                let quantity = parseInt(quantitySpan.textContent);
                if (quantity > 1) {
                    quantity--;
                    quantitySpan.textContent = quantity;
                    updateQuantityInDatabase(quantity, userId, productId, row);
                    window.location.reload();
                }
            });

            increaseBtn.addEventListener('click', () => {
                let quantity = parseInt(quantitySpan.textContent);
                quantity++;
                quantitySpan.textContent = quantity;
                updateQuantityInDatabase(quantity, userId, productId, row);
                window.location.reload();
            });
        });

        // Call updateSummary on page load to ensure the totals are correct
       
        // Handle product removal
        document.querySelectorAll(".remove-btn").forEach(button => {
            button.addEventListener("click", function () {
                const cartId = this.getAttribute("data-cartid");
                const row = this.closest("tr");

                if (!confirm("Are you sure you want to remove this item?")) {
                    return;
                }

                fetch("removeFromCart.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ cartId: cartId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        row.remove();
                        updateSummary();
                        alert("Product removed from cart!");
                    } else {
                        alert("Error removing product.");
                    }
                })
                .catch(error => console.error("Error:", error));
            });
        });

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        function checkBox(btn){
            document.getElementById(btn).style.display = 'block';
        }
    </script>
</body>
</html>