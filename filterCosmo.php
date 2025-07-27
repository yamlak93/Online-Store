<?php
include 'dbConnect.php';

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $selectedCategories = json_decode($_POST['categories'], true);
    if(empty($selectedCategories)){
        echo "<p> No categories selected. </p>";
        exit();
    }

    $placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
    $query = "SELECT * FROM products WHERE category='cosmetics' And subCategory IN ($placeholders)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat("s", count($selectedCategories)), ...$selectedCategories);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        while($row=$result->fetch_assoc()){
            $imageData = base64_encode($row['productImg']);
            $imageSrc = 'data:image/jpeg;base64,' . $imageData;

            echo '<div class="category">
                    <img src="'.$imageSrc.'" alt="Cosmetics" width="100">
                    <h3>'.$row['productName'].'</h3>
                    <p><strong>'.$row['unitPrice'].' ETB</strong></p>
                    <p>'.$row['status'].'</p>
                    <input class="quanInput" type="number" name="preferedQuantity" min="0" placeholder="Quantity">
                    <p class="descriptionP">'.$row['description'].'</p>';
                    
            if ($row['status'] === "Out of Stock") {
                echo '<button style="display: none"> Add to cart </button>';
            } else {
                echo '<button> Add to cart </button>';
            }

            echo '</div>';
        }
    }else {
        echo "<p>No products found.</p>";
    }
}
?>