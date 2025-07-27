<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmetics</title>
    <style>
        .cosmo-container{
            position: absolute;
            top: 50px;
            left: 270px;
            color: white;
            right: 30px;
        }

        .hero{
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            width: 930px;
            height: 200px;
            text-align: center;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
        }


        .hero h1 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .cosmetics-choice {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 50px;
            font-size: 1.5rem;
        }

        .cosmetics-choice input[type="checkbox"] {
            margin-right: 5px;
            zoom: 1.5;
        }

        .cosmetics-choice button {
            background-color: #151922;
            border: none;
            padding: 10px 15px;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100px;
            transition: background 0.3s;
        }

        .cosmetics-choice button:hover {
            background-color: #28231D;
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

        /* Responsive design for mobile devices */
        @media (max-width: 768px) {
            .cosmo-container {
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
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .hero h1 {
                font-size: 24px;
            }

            .cosmetics-choice {
                flex-direction: column;
                align-items: center;
            }

            .cosmetics-choice label {
                margin-bottom: 10px;
            }

            .cosmetics-choice button {
                width: 100%;
                margin-top: 10px;
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
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'?>
    <div class="cosmo-container">
    <div class="hero">
            <h1>Cosmetics</h1>
            <div class="cosmetics-choice">
                <label for="makeup">
                    <input type="checkbox" name="category" id="makeup" value="makeup"> Makeup
                </label>
                <label for="Skincare">
                    <input type="checkbox" name="category" id="Skincare" value="skincare"> Skincare
                </label>
                <label for="Haircare">
                    <input type="checkbox" name="category" id="Haircare" value="haircare"> Haircare
                </label>
                <label for="Fragrances">
                    <input type="checkbox" name="category" id="Fragrances" value="fragrances"> Fragrances
                </label>
                <button id="filter-btn">Filter</button>
                <p id="filterError" style="color: white; text-align: center; font-size: 0.6em; width: 100%;" ></p>
            </div>
        </div>
        <section class="categories">
            <div class="categories-list"> 
            <?php
                include 'dbConnect.php';
                $cat = "cosmetics";
                $stmt = $conn->prepare("SELECT * FROM products WHERE category=?");
                $stmt->bind_param("s", $cat);
                $stmt->execute();
                $result=$stmt->get_result();
                if($result->num_rows > 0)
                {
                    while($row= $result->fetch_assoc())
                    {
                        $imageData = base64_encode($row['productImg']);
                        $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                        echo '<div class="category">
                                    <img src="'.$imageSrc.'" alt="electronics"width="100" >
                                    <h3>'.$row['productName'].'</h3>
                                    <p>'.$row['subCategory'].'</p>
                                    <p style="font-weight: bold; font-size:1.2em;"> '. $row['unitPrice'] .' ETB</p>
                                    <p> '. $row['status'] .' </p>
                                    <input class="quanInput" type="number" name="preferedQuantity" min="0" max="'.$row['Quantity'].'" placeholder="Quantity" >
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
                                        data-subcategory="'.$row['subCategory'].'">
                                         Add to cart </button>';
                                    }
                                
                          echo' </div>';
                    }
                }else{
                    echo '<p> No product found! </P>';
                }
                ?>

            </div>
        </section>
    </div>

    <script>
        document.getElementById("filter-btn").addEventListener("click", function(){
            let selectedCategories = [];
            document.querySelectorAll('input[name="category"]:checked').forEach((checkbox) => {
                selectedCategories.push(checkbox.value);
            });
            if(selectedCategories.length === 0){
                document.getElementById('filterError').innerHTML = "Please select at least one category."
                return;
            }

            let formData = new FormData();
            formData.append("categories", JSON.stringify(selectedCategories));

            fetch("filterCosmo.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.querySelector(".categories-list").innerHTML = data;
            })
            .catch(error => console.error("Error:", error));
        });

        document.querySelectorAll(".addToCartBtn").forEach(button => {
    button.addEventListener("click", function () {
        let categoryDiv = this.closest(".category"); // Get the parent category div
        let quantityInput = categoryDiv.querySelector(".quanInput"); // Find the input in the same product div
        let quantity = quantityInput.value;

        if (!quantity || quantity <= 0) {
            alert("Add Quantity");
            return;
        }

        let productId = this.getAttribute("data-productid");
        let productName = this.getAttribute("data-productname");
        let productImg = this.getAttribute("data-productimg");
        let unitPrice = this.getAttribute("data-unitprice");
        let category = this.getAttribute("data-category");
        let subCategory = this.getAttribute("data-subcategory");

        let formData = new FormData();
        formData.append("productId", productId);
        formData.append("productName", productName);
        formData.append("productImg", productImg);
        formData.append("unitPrice", unitPrice);
        formData.append("quantity", quantity);
        formData.append("category", category);
        formData.append("subCategory", subCategory);

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
            window.location.href = "userLogin.php";
            alert("Login or Sign up");
        });
    });
});

    </script>
</body>
</html>