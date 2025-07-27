<?php
session_start(); // ✅ Ensure session starts at the top
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
body {
    background-color: #151922;
    margin: 0; /* Remove body margin */
    padding: 0; /* Remove body padding */
    display: flex;
    justify-content: flex-start;
    min-height: 100vh; /* Ensures content height is managed */
    overflow-x: hidden; /* Prevent horizontal scroll */
}

.navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    font-size: 18px;
    font-weight: bold;
    background-color: black;
    color: white;
    border-radius: 5px;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%; /* Ensure it stretches the full width */
    z-index: 1000; /* Make sure nav is on top */
    box-sizing: border-box; /* Make sure padding is inside the width */
}

.navigation .menu-icon {
    display: none;
    font-size: 24px;
    cursor: pointer;
}

span {
    cursor: pointer;
}

a {
    text-decoration: none;
    color: white;
}

.sidebar {
    background-color: black;
    height: 100vh;
    width: 230px;
    color: white;
    padding: 10px;
    position: fixed;
    top: 50px; /* Keep it below the nav */
    left: 0;
    z-index: 999;
    box-sizing: border-box; /* To prevent overlap issues */
    overflow-y: auto; /* Allows scroll inside sidebar if needed */
}

.sidebar.active {
    transform: translateX(0);
}

.search {
    position: relative;
    margin-top: 20px; /* Push search input down */
}

.search input {
    padding: 5px;
    width: 100%; /* Take full width minus some padding */
    text-align: center;
}

#searchBtn {
    margin-top: 10px; /* Move below the search input */
    width: 100%; /* Ensure button takes the full width */
    height: 30px;
    background: linear-gradient(to right, #ff416c, #ff4b2b);
    color: white;
    border: none;
    font-size: 15px;
    cursor: pointer;
}

#searchBtn:hover {
    background: #ff4b2b;
}

#searchBtn:active {
    background: #28231D;
}

.choice {
    display: flex;
    flex-direction: column;
    margin-top: 30px; /* Adjust for better spacing */
}

.choice > button {
    margin-bottom: 20px; /* Adjust spacing between buttons */
    height: 40px;
    background-color: #151922;
    color: white;
    border: none;
    font-size: 15px;
    cursor: pointer;
}

.choice > button:hover {
    background-color: #28231D;
}

.choice > button:active {
    background-color: #151922;
}

footer {
    background-color: black;
    color: white;
    padding: 10px;
    text-align: center;
    position: relative; /* Ensure footer isn't fixed */
}
@media (max-width: 768px) {
    .navigation {
        flex-direction: row;
        justify-content: space-between;
        padding: 10px;
    }

    .navigation .menu-icon {
        display: block;
    }

    .navigation span, .navigation a {
        margin-bottom: 0;
    }

    .sidebar {
        width: 100%;
        height: auto;
        position: fixed;
        top: 50px;
        left: 0;
        padding: 10px;
        transform: translateY(-100%);
        transition: transform 0.3s ease-in-out;
    }

    .sidebar.active {
        transform: translateY(0);
    }

    .choice {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }

    .choice > button {
        width: 45%;
        margin: 5px;
    }
}


    </style>
</head>
<body>

<nav class="navigation">
<span class="menu-icon" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
    <span><a href="./dashboard.php">Online Store</a></span>
    <span><a href="./cart.php">Cart</a></span>
    <span><a href="./orders.php">Orders</a></span>

    <?php
    if (isset($_SESSION['userId'])) {  // ✅ Use correct session variable name
        echo '<a href="profile.php"><span>Profile</span></a>';
    } else {
        echo '<a href="userLogin.php"><span>Login</span></a>';
    }
    ?>        
</nav>

    <div class="sidebar" id="sidebar">
        <form action="search-result.php" method="POST" class="search">
            <input name="searchReq" type="search" placeholder="Search" id ="searchReq" required>
            <button type="submit" id="searchBtn"><i class="fas fa-search"></i> Search</button>
        </form>

        <div class="choice">
            <button onclick="window.location.href='clothes.php'">Clothes</button>
            <button onclick="window.location.href='shoes.php'">Shoes</button>
            <button onclick="window.location.href='electronics.php'">Electronics</button>
            <button onclick="window.location.href='cosmo.php'">Cosmetics</button>
            <button onclick="window.location.href='furniture.php'">Furniture</button>
        </div>
    </div>
</body>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
    }

    document.getElementById("searchBtn").addEventListener("click", function(event) {
        let reqSer = document.getElementById('searchReq').value.trim(); 

        if (reqSer === "") {
            alert("Nothing to be searched");
            event.preventDefault(); 
            return; 
        }
    });
</script>
</html>