<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>

        .dashboard {
            margin-left: 270px;
            padding: 20px;
            width: 100%;
        }

        .hero {
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            width: 100%;
            height: 100px;
            text-align: center;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
        }

        /* Categories & Products */
        .categories, .best-products {
            margin-top: 20px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .categories h2, .best-products h2 {
            text-align: center;
            color: #333;
        }

        .categories-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .category {
            text-align: center;
            width: 200px;
            padding: 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .category:hover {
            transform: scale(1.07);
        }

        .category img {
            width: 180px;
            height: 140px;
            border-radius: 5px;
        }

        /* Responsive design for mobile devices */
        @media (max-width: 768px) {
            .dashboard {
                margin-left: 0;
                padding: 10px;
                width: 100%;
            }

            .hero {
                height: auto;
                padding: 20px;
                flex-direction: column;
                align-items: center;
            }

            .hero h1 {
                font-size: 24px;
            }

            .categories-list {
                flex-direction: column;
                align-items: center;
            }

            .category {
                width: 100%;
                margin: 10px 0;
            }

            .category img {
                width: 150px; /* Adjust the width for smaller devices */
                height: auto;
            }
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>

    <!-- Main Dashboard -->
    <div class="dashboard">

        <!-- Hero Section -->
        <div class="hero">
            <h1>Welcome to Our Store!</h1>
        </div>

        <!-- Categories Section -->
        <section class="categories">
            <h2>Shop Categories</h2>
            <div class="categories-list">
                <div class="category" onclick="window.location.href='clothes.php'">
                    <img src="imgs/clothes-banner.jpg" alt="Clothes">
                    <h3>Clothes</h3>
                </div>
                <div class="category" onclick="window.location.href='shoes.php'">
                    <img src="imgs/shoes-banner.jpg" alt="Shoes">
                    <h3>Shoes</h3>
                </div>
                <div class="category" onclick="window.location.href='electronics.php'">
                    <img src="imgs/electronics-banner.jpg" alt="Electronics">
                    <h3>Electronics</h3>
                </div>
                <div class="category" onclick="window.location.href='cosmo.php'">
                    <img src="imgs/cosmetics-banner.jpg" alt="Cosmetics">
                    <h3>Cosmetics</h3>
                </div>
                <div class="category" onclick="window.location.href='furniture.php'">
                    <img src="imgs/furniture-banner.jpg" alt="Furniture">
                    <h3>Furniture</h3>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="best-products">
            <h2>Featured Products</h2>
            <div class="categories-list">
                <div class="category" onclick="window.location.href='clothes.php'">
                    <img src="imgs/menClothes-banner.jpg" alt="Men Clothes">
                    <h3>Men Clothes</h3>
                </div>
                <div class="category" onclick="window.location.href='clothes.php'">
                    <img src="imgs/femaleClothes-banner.jpg" alt="Female Clothes">
                    <h3>Female Clothes</h3>
                </div>
                <div class="category" onclick="window.location.href='shoes.php'">
                    <img src="imgs/menShoes-banner.jpg" alt="Men Shoes">
                    <h3>Men Shoes</h3>
                </div>
                <div class="category" onclick="window.location.href='shoes.php'">
                    <img src="imgs/femaleShoes-banner.jpg" alt="Female Shoes">
                    <h3>Female Shoes</h3>
                </div>
                <div class="category" onclick="window.location.href='electronics.php'">
                    <img src="imgs/samsungTv-banner.jpg" alt="TV">
                    <h3>TV</h3>
                </div>
            </div>
        </section>

    </div>

</body>
</html>
