<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>
    <style>
        .result-container{
            position: absolute;
            top: 50px;
            left: 270px;
            color: white;
            right: 30px;
        }

        .hero{
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            width: 930px;
            height: 100px;
            text-align: center;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
        }


        .hero h1 {
            font-size: 32px;
            margin-bottom: 15px;
        }
        .category{
            text-align: center;
            width: 250px;
            padding: 5px;
            margin: 10px;
            cursor: pointer;
            border: 2px solid #ff416c;
            border-radius: 5px;
            box-shadow: 0 4px 8px #ff416c;
            height: fit-content;
            transition: 0.6s ease-in-out;
        }

        .category:hover{
            transform: scale(1.07);
            border: 1px solid orange;
            box-shadow: 0 4px 8px orange;
            margin: 20px;
            transition: 0.6s ease-in-out;
            height: fit-content;
            
        }

        .category .descriptionP {
            display: none;
            margin-top: 5px;
            color: #333;
            background: #fff3e0;
            padding: 8px;
            word-wrap: break-word; 
            overflow-wrap: break-word;
            border: 1px solid orange;
            border-radius: 5px;
            transition: opacity 0.4s ease-in-out, height 0.4s ease-in-out;
        }

        /* Show the description on hover */
        .category:hover .descriptionP {
            display: block;
        }

        .categories>h2{
            text-align: center;
        }

        .category>img{
            width: 200px;
            height:150px;
        }

        .categories{
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
            width: fit-content;
        }

        .categories-list{
            display: flex;
            flex-wrap: wrap;
        }

        
        .category button {
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            margin-bottom: 5px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.3s ease;
        }

        .category button:hover {
            background: linear-gradient(45deg, #ff4b2b, #ff416c);
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(255, 65, 108, 0.5);
        }

        .category button:active {
            transform: scale(0.95);
            box-shadow: 0 2px 5px rgba(255, 65, 108, 0.5);
        }

        .quanInput{
            width: 100%;
            height: 30px;
            border: none;
            text-align: center;
            
        }
        @media (max-width: 768px) {
        .result-container {
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

        .categories-list {
            flex-direction: column;
            align-items: center;
        }

        .category {
                width: 150px;
                height: 400px;
                margin: 10px 0;
            }

            .category img {
                width: 100px;
                height: 100px;;
            }

        .category button {
            width: 100%;
            padding: 10px;
            font-size: 14px;
        }


        
    }
    </style>
</head>
<body>
    <?php include 'navigation.php'?>
    <div class="result-container">
        <div class="hero">
                <h1>Search Result</h1>
        </div>
        <div class="categories">
            <div class="categories-list">
                <?php
                include 'dbConnect.php';
                if($_SERVER['REQUEST_METHOD'] === "POST")
                {
                    $request = $_POST['searchReq'];
                    
                    $stmt = $conn->prepare("SELECT * FROM products WHERE productName=?");
                    $stmt->bind_param("s", $request);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows > 0)
                    {
                        while($row = $result->fetch_assoc())
                        {
                            $imageData = base64_encode($row['productImg']);
                            $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                            $cat = $row['category'];
                            echo '<div class="category">
                                        <img src="'.$imageSrc.'" alt="'.$cat.'"width="100" >
                                        <h3>'.$row['productName'].'</h3>
                                        <p style="font-weight: bold; font-size:1.2em;"> '. $row['unitPrice'] .' ETB</p>
                                        <p> '. $row['status'] .' </p>
                                        <input class="quanInput" type="number" name="preferedQuantity" min="0" placeholder="Quantity" >
                                        <p class="descriptionP"> '. $row['description'] .'</p>
                                        ';
                                        $st = $row['status'];
                                        if( $st === "Out of Stock")
                                        {
                                         echo ' <button style="display: none"> Add to cart </button>';
                                        }else{
                                            echo '<button class="addToCartBtn"
                                            data-productid="'.$row['productId'].'"
                                            data-productname="'.$row['productName'].'"
                                            data-productimg="'.$imageSrc.'"
                                            data-unitprice="'.$row['unitPrice'].'"
                                            data-category="'.$row['category'].'"
                                            data-subcategory="'.$row['subCategory'].'"
                                            data-userId="'.$_SESSION['userId'].'">
                                             Add to cart </button>';
                                        }
                                    
                            echo' </div>';
                        }
                    }else{
                        echo "  <p> Product Not Found. </p>";
                    }
                }
                
                ?>
            </div>
           
        </div>
    </div>
    <script>
        document.querySelectorAll(".addToCartBtn").forEach(button => {
    button.addEventListener("click", function () {
        let categoryDiv = this.closest(".category"); // Get the parent category div
        let quantityInput = categoryDiv.querySelector(".quanInput"); // Find the input in the same product div
        let quantity = quantityInput.value;

        if (!quantity || quantity <= 0) {
            alert("Add Quantity.");
            return;
        }

        let productId = this.getAttribute("data-productid");
        let productName = this.getAttribute("data-productname");
        let productImg = this.getAttribute("data-productimg");
        let unitPrice = this.getAttribute("data-unitprice");
        let category = this.getAttribute("data-category");
        let subCategory = this.getAttribute("data-subcategory");
        let userId = this.getAttribute("data-userId");

        let formData = new FormData();
        formData.append("productId", productId);
        formData.append("productName", productName);
        formData.append("productImg", productImg);
        formData.append("unitPrice", unitPrice);
        formData.append("quantity", quantity);
        formData.append("category", category);
        formData.append("subCategory", subCategory);
        formData.append("userId", userId);

        fetch("addToCart.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.innerText = "Added";
                this.disabled = true;
                alert("Product added to cart!");
            } else {
                alert("Error adding product to cart: " + (data.error || "Unknown error"));
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            alert("An error occurred while adding to cart.");
        });
    });
});
    </script>
</body>

</html>