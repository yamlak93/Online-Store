<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            background-color: #151922;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .report-container {
            position: absolute;
            top: 50px;
            left: 270px;
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

        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .stat-card {
            background: #222;
            padding: 15px;
            width: 30%;
            text-align: center;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(255, 255, 255, 0.1);
        }

        .stat-card h2 {
            margin: 0;
            font-size: 20px;
        }

        .stat-card p {
            font-size: 24px;
            margin: 10px 0;
            font-weight: bold;
        }

        .report-table {
            width: 958px;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 5px;
            overflow: hidden;
        }

        .report-table th, .report-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #444;
        }

        .report-table th {
            background-color: #ff4b2b;
        }

        .stock-badge {
            padding: 5px 10px;
            border-radius: 3px;
        }

        .in-stock { background-color: green; }
        .low-stock { background-color: orange; }
        .out-of-stock { background-color: red; }

        .export-btn {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .export-btn:hover {
            background-color: #218838;
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

<div class="report-container">
    <div class="hero">
        <h1>Sales & Stock Report</h1>
    </div>

    <!-- Summary Stats -->
    <div class="stats-container">
        <div class="stat-card">
        <?php
                include 'dbConnect.php';
                $sql = "SELECT SUM(totalPrice) AS totalSales FROM orders";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $totalSales = $row['totalSales']??0;

            ?>
            <h2>Total Sales</h2>
            <p>$<?php echo $totalSales;?></p>
        </div>
        <div class="stat-card">
        <?php
                include 'dbConnect.php';
                $sql = "SELECT COUNT(*) AS totalOrders FROM orders";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $totalOrders = $row['totalOrders']??0;

            ?>
            <h2>Total Orders</h2>
            <p><?php echo $totalOrders;?></p>
        </div>
        <div class="stat-card">
            <?php
            if($totalOrders > 0)
            {
                $avgOrder = $totalSales / $totalOrders;
            }else{
                $avgOrder = 0;
            }
            
            
            ?>
            <h2>Average Order Value</h2>
            <p>$<?php echo $avgOrder;?></p>
        </div>
    </div>

    <!-- Stock Report -->
    <h2>Stock Report</h2>
    <span class="search">
                <input type="text" id="searchInput" placeholder="Enter Product ID">
                <span id="clearBtn" style="display: none;" onclick="clearSearch()">
                    <button class="status-btn" style="color: white;">&times;</button>
                </span>
                <button class="search-btn" onclick="searchProduct()">Search</button>
            </span>
    <table class="report-table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th >Stock</th>
                <th>Total Sales</th>
                <th>Tax&Trans</th>
                <th>Gross Revenue</th>
            </tr>
        </thead>
        <tbody>
        <?php
                include 'dbConnect.php';

                $stmtReport = $conn->prepare("SELECT * FROM products");
                $stmtReport->execute();
                $resultReport = $stmtReport->get_result();

                if ($resultReport->num_rows > 0) {
                    while ($report = $resultReport->fetch_assoc()) {
                        echo '<tr productId="' . $report['productId'] . '">
                            <td>' . $report['productId'] . '</td>
                            <td>' . $report['productName'] . '</td>
                            <td>' . $report['category'] . '</td>
                            <td>$' . $report['unitPrice'] . '</td>';

                        // Handle stock status display
                        if ($report['status'] === "Out of Stock") {
                            echo '<td><span class="stock-badge out-of-stock">' . $report['status'] . ' (' . ($report['Quantity'] ?? 0) . ')</span></td>';

                        } elseif ($report['Quantity'] < 5) {
                            echo '<td><span class="stock-badge low-stock">' . $report['status'] . ' (' . ($report['Quantity'] ?? 0) . ')</span></td>';
                        } else {
                            echo '<td><span class="stock-badge in-stock">' . $report['status'] . ' (' . ($report['Quantity'] ?? 0) . ')</span></td>';
                        }

                        $productId = $report['productId']; 
                        $sql = "SELECT SUM(soldQuantity) AS totalSales FROM sales WHERE productId = ?";
                        $salesStmt = $conn->prepare($sql);

                        if (!$salesStmt) {
                            die("SQL Error: " . $conn->error); 
                        }

                        $salesStmt->bind_param("s", $productId); 
                        $salesStmt->execute();
                        $result = $salesStmt->get_result();
                        $row = $result->fetch_assoc();
                        $totalSales = $row['totalSales'] ?? 0; 
                        $unitPrice = $report['unitPrice'];
                        $totalRev = $totalSales * $unitPrice;
                        $tax = $totalRev * 0.15;
                        $shipment = $totalRev * 0.02;
                        $tAndS = $tax + $shipment;
                        $grossRev = $totalRev + $tAndS; 
                        echo '<td>' . $totalSales . '</td>
                            <td>' . $tAndS . '</td>
                            <td>$'. $grossRev .'</td>
                        </tr>';
                    }
                }else{
                    echo "<tr><td colspan='8' style='text-align: center;'>You don't have any products!</td></tr>";
                }
            ?>
        </tbody>
    </table>

    <button class="export-btn" onclick="exportToCSV()">Export as CSV</button>
</div>

<script>
     function searchProduct() {
    let input = document.getElementById("searchInput").value.trim(); // Get user input
    let rows = document.querySelectorAll("tr[productId]"); // Select all table rows with a 'productId' attribute

    if (input === "") {
        alert("Please enter a product id!");
        return;
    }

    let found = false;

    rows.forEach(row => {
        let productId = row.getAttribute("productId"); // Get productId from the row's productId attribute

        if (productId === input) {
            row.style.display = ""; // Show matching row
            found = true;
        } else {
            row.style.display = "none"; // Hide non-matching rows
        }
    });

    // Show "X" button if search input is not empty
    document.getElementById("clearBtn").style.display = input ? "inline" : "none";

    if (!found) {
        alert("Product with this Product ID not found!");
    }
}

function clearSearch() {
    // Clear the input field
    document.getElementById("searchInput").value = "";

    // Show all rows again
    let rows = document.querySelectorAll("tr[productId]");
    rows.forEach(row => {
        row.style.display = ""; // Show all rows
    });

    // Hide the "X" button
    document.getElementById("clearBtn").style.display = "none";
}

    function exportToCSV() {
        let table = document.querySelector(".report-table");
        let rows = table.querySelectorAll("tr");

        let csvContent = "Product ID, Name, Category, Price, Stock, Total Sales, Tax and shipment, Total Revenue\n";
        rows.forEach((row, index) => {
            let cols = row.querySelectorAll("td, th");
            let rowData = [];
            cols.forEach(col => rowData.push(col.innerText));
            if (index > 0) {
                csvContent += rowData.join(",") + "\n";
            }
        });

        let blob = new Blob([csvContent], { type: "text/csv" });
        let link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "sales_report.csv";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

</body>
</html>
