<?php
include 'dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice']; 
    $stockQuantity = $_POST['stockQuantity'];
    $category = $_POST['category'];
    $subcategory = $_POST['subCategory'];
    $description = $_POST['description'];
    $imageData = null;

    if ($category == "") {
        echo "<script> alert('Please select a category!'); </script>";
        exit();
    }

    $status = ($stockQuantity > 0) ? "In Stock" : "Out of Stock";

    // Debugging: Check if file was uploaded
    if (isset($_FILES['productImg']) && $_FILES['productImg']['error'] === 0) {
        if ($_FILES['productImg']['size'] > 5 * 1024 * 1024) { // 5MB limit
            echo "<script>alert('File size exceeds 5MB limit.');</script>";
            exit();
        }
        
        // Debugging: Ensure file exists
        if (is_uploaded_file($_FILES['productImg']['tmp_name'])) {
            $imageData = file_get_contents($_FILES['productImg']['tmp_name']);
        } else {
            echo "<script>alert('Error: Image file not found!');</script>";
            exit();
        }
    } else {
        echo "<script>alert('No image uploaded or error in upload!');</script>";
    }

    // Check if product already exists
    $checkstmt = $conn->prepare("SELECT * FROM products WHERE productName = ?");
    $checkstmt->bind_param("s", $productName); 
    $checkstmt->execute();
    $result = $checkstmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script> alert('Product exists. Updating details...'); window.location.href='adminProduct.php' </script>";

        $stmt = $conn->prepare("UPDATE products SET category=?, subCategory=?, unitPrice=?, description=?, Quantity=?, status=?, productImg=? WHERE productName=?");
        $stmt->bind_param("ssdsisbs", $category, $subcategory, $productPrice, $description, $stockQuantity, $status, $imageData, $productName);

        // ✅ Fix: Send BLOB data properly
        $stmt->send_long_data(6, $imageData); 
        $stmt->execute();
    } else {
        // Generate unique productId
        do {
            $productId = uniqid('PRD_');
            $checkProId = $conn->prepare("SELECT productId FROM products WHERE productId=?");
            $checkProId->bind_param("s", $productId);
            $checkProId->execute();
            $idExists = $checkProId->get_result()->num_rows > 0;
        } while ($idExists);

        $conn->begin_transaction();
        try {
            // ✅ Fix: Correct column name & use `send_long_data()`
            $stmt = $conn->prepare("INSERT INTO products (productId, productName, category,subCategory, description, unitPrice, Quantity, status, productImg)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssdisb", $productId, $productName, $category, $subcategory, $description, $productPrice, $stockQuantity, $status, $imageData);

            // ✅ Send image data separately
            $stmt->send_long_data(8, $imageData);

            if ($stmt->execute()) {
                $conn->commit();
                echo "<script>window.location.href='adminProduct.php'</script>";

            } else {
                throw new Exception("Error executing query: " . $stmt->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Error in adding product: " . $e->getMessage() . "');</script>";
        }
    }
}
?>
