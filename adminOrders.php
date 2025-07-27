<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order-Management</title>
    <style>
        body {
            background-color: #151922;
            color: white;
            font-family: Arial, sans-serif;
        }

        .orders-container {
            position: absolute;
            top: 50px;
            left: 270px;
            width: calc(100% - 290px);
            overflow-x: scroll;
        }

        .hero {
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            width: 958px;
            height: 100px;
            text-align: center;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
        }

        .orders-table {
            width: 300px;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 5px;
            
        }

        .orders-table th, .orders-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .orders-table th {
            background-color: #ff4b2b;
        }

        .status-btn {
            padding: 6px 12px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
            font-size: 14px;
            color: white;
        }

        .pending { background-color: orange; }
        .shipped { background-color: blue; }
        .delivered { background-color: green; }
        .cancelled { background-color: red; }

        .status-btn:hover {
            opacity: 0.8;
        }

        .checkmark {
            color: green;
            font-size: 18px;
        }
        .search{
            margin: 20px;
            padding: 30px;
        }
        input {
            width: 300px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            outline: none;
        }
        .search-btn{
            width: 100px;
            padding: 10px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            outline: none;
            cursor: pointer;
        }
        .search-btn:hover{
            background-color: gray;
        }

        #clearBtn {
            cursor: pointer;
            margin-left: 5px;
            font-size: 18px;
            color: black;
        }

        #clearBtn button {
            cursor: pointer;
            background-color: transparent;
            border: none;
            font-size: 18px;
            color: black;
        }
    </style>
</head>
<body>
    <?php include 'adminNav.php'; ?>
    <div class="orders-container">
        <div class="hero">
            <h1>Order Management</h1>
        </div>
        <div class="order-content">
       
            <span class="search">
                <input type="text" id="searchInput" placeholder="Enter Order ID">
                <span id="clearBtn" style="display: none;" onclick="clearSearch()">
                    <button class="status-btn" style="color: white;">&times;</button>
                </span>
                <button class="search-btn" onclick="searchOrder()">Search</button>
            </span>
            
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
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    include 'dbConnect.php';
                    $stmt = $conn->prepare("SELECT * FROM orders");
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
                            echo '<tr data-order-id="'.$row['orderId'].'">
                                    <td>'.$row['orderId'].'</td>
                                    <td>'.$row['userName'].'</td>
                                    <td>'.$row['shippingAddress'].'</td>
                                    <td>'.$row['phone'].'</td>
                                    <td>'.$row['productsName'].'</td>
                                    <td>'.$row['totalPrice'].'</td>
                                    <td>'.$row['paymentOption'].'</td>
                                    <td>'.$row['accountNumber'].'</td>
                                    <td>'.$row['orderDate'].'</td>
                                    <td><span class="status '.$sta.'">'.$sta.'</span></td>
                                    <td>';
                                    
                                    if ($sta == "pending") {
                                        echo '<button class="status-btn shipped" onclick="updateStatus(this, \'shipped\')">Ship</button>';
                                        echo '<button class="status-btn cancelled" onclick="updateStatus(this, \'cancelled\')">Cancel</button>'; 
                                    } elseif ($sta == "shipped") {
                                        echo '<button class="status-btn delivered" onclick="updateStatus(this, \'delivered\')">Deliver</button>';
                                        echo '<button class="status-btn pending" onclick="updateStatus(this, \'pending\')">Reverse to Pending</button>';
                                    } elseif ($sta == "delivered" || $sta == "cancelled") {
                                        echo '<button class="status-btn pending" onclick="updateStatus(this, \'pending\')">Reverse to Pending</button>';
                                    }
                                    
                                    
                                    
                        
                            echo '</td></tr>';
                        }
                        
                    }else{
                        echo "<tr><td colspan='10' style='text-align: center;'>You don't have any order!</td></tr>";
                    }
                    ?>
 
                </tbody>
            </table>
        </div>
    </div>
    

    <script>
        function searchOrder() {
            let input = document.getElementById("searchInput").value.trim(); // Get user input
            let rows = document.querySelectorAll(".orders-table tbody tr"); // Select all table rows

            if (input === "") {
                alert("Please enter an Order ID!");
                return;
            }

            let found = false;

            rows.forEach(row => {
                let orderId = row.getAttribute("data-order-id"); // Get Order ID from row

                if (orderId === input) {
                    row.style.display = ""; // Show matching row
                    found = true;
                } else {
                    row.style.display = "none"; // Hide non-matching rows
                }
            });

            // Show "X" button if search input is not empty
            document.getElementById("clearBtn").style.display = input ? "inline" : "none";

            if (!found) {
                alert("Order ID not found!");
            }
        }

        function clearSearch() {
            // Clear the input field
            document.getElementById("searchInput").value = "";
            
            // Show all rows again
            let rows = document.querySelectorAll(".orders-table tbody tr");
            rows.forEach(row => {
                row.style.display = ""; // Show all rows
            });

            // Hide the "X" button
            document.getElementById("clearBtn").style.display = "none";
        }


        function updateStatus(btn, newStatus) {
    let row = btn.closest('tr');
    let orderId = row.getAttribute('data-order-id');
    let statusCell = row.querySelector('td:nth-child(10) span'); // Corrected column index for status

    let formData = new FormData();
    formData.append('orderId', orderId);
    formData.append('status', newStatus);

    fetch('update_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Server Response:", data); // Debugging output

        if (data.trim() === 'success') {
            statusCell.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
            statusCell.className = 'status ' + newStatus;

            let actionCell = btn.closest('td');
            actionCell.innerHTML = '';

            if (newStatus === 'shipped') {
                actionCell.innerHTML = `
                    <button class="status-btn delivered" onclick="updateStatus(this, 'delivered')">Deliver</button>
                    <button class="status-btn pending" onclick="updateStatus(this, 'pending')">Reverse to Pending</button>
                `;
            } else if (newStatus === 'delivered' || newStatus === 'cancelled') {
                actionCell.innerHTML = `<button class="status-btn pending" onclick="updateStatus(this, 'pending')">Reverse to Pending</button>`;
            } else if (newStatus === 'pending') {
                actionCell.innerHTML = `
                    <button class="status-btn shipped" onclick="updateStatus(this, 'shipped')">Ship</button>
                    <button class="status-btn cancelled" onclick="updateStatus(this, 'cancelled')">Cancel</button>
                `;
            }
        } else {
            alert("Error updating status: " + data);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert("Failed to send request.");
    });
}



    </script>
</body>
</html>
