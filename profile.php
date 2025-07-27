<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: userLogin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
    <style>
        .profile{
            position: absolute;
            top: 50px;
            left: 270px;
            color: white;
            width: 60%;
            margin: auto;
            padding: 20px;
            box-shadow: 0px 6px 12px rgba(0,0,0,0.1);
           
            text-align: center;
        }


        .hero {
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            color: white;
            height: 70px;
            font-size: 26px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
            display: flex;
            align-items: center;
            display: flex;
            justify-content: space-between; /* Aligns title and button */
            padding: 0 20px;
        }

        .logout-btn {
            background: white;
            color: #ff416c;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: orange;
            color: white;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-top: 5px;
            border: 5px solid white;
        }
        .profile-info {
            margin-top: 20px;
            text-align: left;
            padding: 0 30px;
        }
        .profile-info label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        .profile-info input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .save-btn {
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            display: block;
            margin: 20px auto;
            width: 60%;
            border-radius: 5px;
            transition: 0.3s;
        }
        .save-btn:hover {
            background: linear-gradient(to right, #ff4b2b, #ff416c);
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 45%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #555;
        }
        .password-container {
            position: relative;
        }

        @media (max-width: 768px) {
        .profile {
            position: absolute;
                left: 5px;
                right: 5px;
                padding: 10px;
                margin: 0 auto;
                width: 100%;
                max-width: 600px;
        }

        .hero {
            width: 450px;
            height: auto;
            padding: 20px;
            margin-top: 10px;
        }

        .profile-info {
            padding: 0 10px;
            width: 450px;
        }

        .profile-info input {
            padding: 10px;
            font-size: 14px;
        }

        .save-btn {
            width: 80%;
            padding: 10px;
            font-size: 16px;
        }
    }
        
    </style>
</head>
<body>
    <?php
        include 'navigation.php';
   
        include 'dbConnect.php';

        $stmt = $conn->prepare("SELECT * FROM userdata WHERE userId = ?");
        $userId = $_SESSION['userId'];
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    ?>

    <div class="profile">
        <div class="hero"><h1>My Profile</h1> <button class="logout-btn" onclick="window.location.href='userLogout.php'">Logout</button></div>
            
            <form class="profile-info" id="profileForm">
                <div class="profileForm">
                <label for="fullName">Full Name</label>
                <input type="text" name="fullName" value="<?php echo $user['fullName']; ?>">
                <label for="email">Email Address</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>">
                <label for="address">Phone Number</label>
                <input type="text" name="phone" value="<?php echo $user['phone']; ?>">
                <label for="address">Address</label>
                <input type="text" name="address" value="<?php echo $user['userAddress']; ?>">
                <label for="password">New Password (Leave blank to keep old)</label>
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Enter new password">
                    <span class="toggle-password" onclick="togglePassword()">👁️</span>
                </div>
                <button type="submit" class="save-btn">Save changes</button>
                <input type="hidden" name="userId" value="<?php echo $user['userId']; ?>">

                </div>

            </form>
    </div>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }

        document.getElementById("profileForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent page reload
            
            var formData = new FormData(this);
            
            fetch("updateUserProfile.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
            })
            .catch(error => console.error("Error:", error));
        });
    </script>
</body>
</html>