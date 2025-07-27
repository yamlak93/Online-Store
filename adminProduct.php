<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product-Management</title>
    <style>
        .products-container{
            position: absolute;
            top:50px;
            left: 270px;
            width: calc(100% -290px);
            color: white;
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

        .products-table {
            width: 958px;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 5px;
            overflow: hidden;
        }

        .products-table th, .products-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .products-table th {
            background-color: #ff4b2b;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
            font-size: 14px;
            color: white;
            margin: 2px;
        }

        .delete { background-color: red; }
        .stock { background-color: blue; }
        .add { background-color: green; }

        .action-btn:hover {
            opacity: 0.8;
        }

        .add-product-form {
            margin-top: 20px;
            background: #222;
            padding: 20px;
            width: 500px;
            border-radius: 5px;
        }

        .add-product-form input, .add-product-form button, .add-product-form select, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
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
    <div class="products-container">
        <div class="hero">
            <h1>Product Management</h1>
        </div>

        <form action="addProducts.php" method="POST" enctype="multipart/form-data" class="add-product-form">
            <h2>Add New Product</h2>
            <label for="productName">Product Name</label>
            <input type="text" name="productName" id="productName" placeholder="eg. Samsung tv" required>
            <label for="mainCategory">Main Category</label>
            <select name="category" id="mainCategory" onchange="updateSubCategories()">
                <option value="">Select a Category</option>
                <option value="electronics">Electronics</option>
                <option value="clothes">Clothing</option>
                <option value="shoes">Shoes</option>
                <option value="cosmetics">Cosmetics</option>
                <option value="furniture">Furniture</option>
            </select>

            <label for="subCategory">Subcategory</label>
            <select name="subCategory" id="subCategory">
                <option value="">Select a Subcategory</option>
            </select>
            <label for="description">Product Description</label>
            <textarea name="description" id="description" placeholder="eg.(color: black....)"></textarea>
            <label for="productPrice">Price</label>
            <input type="number" name="productPrice" id="productPrice" placeholder="eg. 1000 ETB" required>
            <label for="productImg">Product Image</label>
            <input type="file" name="productImg" id="productImg" accept="image/*" required onchange="checkFileSize()">
            <label for="productStock">Stock Quantity</label>
            <input type="number" name="stockQuantity" id="productStock" placeholder="eg. 20" required>
            <button type="submit" class="action-btn add" >Add Product</button>
     </form>

        <div class="products-content">
            <span class="search">
                <input type="text" id="searchInput" placeholder="Enter Product ID">
                <span id="clearBtn" style="display: none;" onclick="clearSearch()">
                    <button class="status-btn" style="color: white;">&times;</button>
                </span>
                <button class="search-btn" onclick="searchProduct()">Search</button>
            </span>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Sub-Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <?php
                        include 'dbConnect.php';
                        $stmt = "SELECT * FROM products";
                        $result = $conn->query($stmt);
                        if($result->num_rows > 0)
                        {
                            while ($row = $result->fetch_assoc()) {
                                // Convert binary image data to Base64
                                $imageData = base64_encode($row['productImg']);
                                $imageSrc = 'data:image/jpeg;base64,' . $imageData; // Change image/jpeg to image/png if needed
                            
                                echo '<tr productId="' . $row['productId'] . '">
                                <td>' . $row['productId'] . '</td>
                                <td><img src="' . $imageSrc . '" alt="Product Image" width="100"></td>
                                <td>' . $row['productName'] . '</td>
                                <td>' . $row['category'] . '</td>
                                <td>' . $row['subCategory'] . '</td>
                                <td>$' . $row['unitPrice'] . '</td>
                                <td>' . $row['Quantity'] . '</td>
                                <td><span class="stock-badge in-stock">' . $row['status'] . '</span></td>
                                <td>
                                    <button class="action-btn stock" onclick="toggleStock(this)">Update Stock</button>
                                    <button class="action-btn delete" onclick="deleteProduct(\'' . $row['productId'] . '\')">Delete</button>
                                </td>
                              </tr>';
                        
                            }
                            
                        }else{
                            echo "<tr><td colspan='8' style='text-align: center;'>No Product found</td></tr>";
                            
                        }
                    ?>
                </tbody>
            </table>
        </div>
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



const subCategories = {
    electronics: ["Mobile Devices", "Computers & Accessories", "Home Appliances", "TV & Audio","Gaming"],
    clothes: ["Men's Wear", "Women's Wear", "Kids' Wear", "Sportswear"],
    shoes: ["Men's Shoes", "Women's Shoes", "Kids' Shoes", "Sports & Outdoor Shoes"],
    cosmetics: ["Makeup", "Skincare", "Haircare", "Fragrances"],
    furniture: ["Living Room", "Bedroom", "Dining Room", "Office Furniture", "Outdoor & Patio"]
};

function updateSubCategories() {
    const mainCategory = document.getElementById("mainCategory").value;
    const subCategory = document.getElementById("subCategory");

    // Clear existing options
    subCategory.innerHTML = '<option value="">Select a Subcategory</option>';

    if (mainCategory && subCategories[mainCategory]) {
        subCategories[mainCategory].forEach(sub => {
            let option = document.createElement("option");
            option.value = sub.toLowerCase().replace(/ /g, "-");
            option.textContent = sub;
            subCategory.appendChild(option);
        });
    }
}


    function checkFileSize() {
            var fileInput = document.getElementById('productImg');
            var file = fileInput.files[0]; // Get the first file
            if (file && file.size > 5 * 1024 * 1024) { // 5MB
                alert('File size exceeds 5MB limit.');
                fileInput.value = ''; // Clear the input
            }
        }
        function deleteProduct(productId) {
            if (!productId) {
                alert("Error: Product ID is undefined!");
                return;
            }

            console.log("Attempting to delete product with ID:", productId); // Debugging

            if (confirm("Are you sure you want to delete this product?")) {
                fetch('deleteProduct.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'productId=' + encodeURIComponent(productId)
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Server response:", data); // Debugging

                    if (data.success) {
                        let row = document.getElementById("row_" + productId);
                        console.log("Trying to remove row with ID:", "row_" + productId, "Found row:", row); // Debugging

                        if (row) {
                            row.remove();
                            alert("Product deleted successfully.");
                        } else {
                            alert("Error: Could not find row to remove.");
                        }
                    } else {
                        alert("Failed to delete product: " + (data.error || "Unknown error"));
                    }
                })
                .catch(error => {
                    alert("Error deleting product: " + error);
                });
            }
        }


function toggleStock(btn) {
    let row = btn.closest('tr');
    let stockBadge = row.querySelector('.stock-badge');
    let productId = row.getAttribute('id').replace('row_', ''); // Extract product ID
    let newStatus = stockBadge.textContent === 'In Stock' ? 'Out of Stock' : 'In Stock';

    // Debugging: Log the data being sent
    console.log("Product ID:", productId);
    console.log("New Status:", newStatus);

    // Update the UI first
    stockBadge.textContent = newStatus;
    stockBadge.classList.toggle('in-stock');
    stockBadge.classList.toggle('out-of-stock');

    // Send AJAX request to update the status in the database
    fetch('updateStock.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'productId=' + encodeURIComponent(productId) + '&status=' + encodeURIComponent(newStatus)
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server Response:", data); // Log the server response

        if (data.success) {
            // Successfully updated the stock status
            alert("Stock status updated successfully!");
        } else {
            alert("Failed to update stock status: " + (data.error || "Unknown error"));
        }
    })
    .catch(error => {
        alert("Error updating stock: " + error);
    });
}


       
    </script>
</body>
</html>