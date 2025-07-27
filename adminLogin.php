<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Login & Signup</title>
    <style>
        /* General Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #0d0d0d;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        /* Container */
        .auth-container {
            position: relative;
            width: 400px;
            height: 520px;
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            border-radius: 20px;
            box-shadow: 0px 0px 30px rgba(255, 75, 43, 0.5);
            overflow: hidden;
            padding: 20px;
            transition: all 0.5s ease-in-out;
        }

        /* Forms */
        .form-box {
            width: 350px;
            position: absolute;
            transition: 0.5s ease-in-out;
            text-align: center;
        }

        /* Hide the signup initially */
        .signup-box {
            display: none;
        }

        /* Form Fields */
        .form-box h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* Buttons */
        .action-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            background: #222;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }

        .action-btn:hover {
            background-color: orange;
        }

        /* Toggle Links */
        .toggle-link {
            text-align: center;
            display: block;
            margin-top: 15px;
            cursor: pointer;
            color: white;
            font-size: 14px;
        }

        .toggle-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <!-- LOGIN FORM -->
    <form action="" method="POST" class="form-box login-box" id="loginBox">
        <h2>Welcome Back! 👋</h2>
        <div class="input-group">
            <input type="email" name="email" id="loginEmail" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" id="loginPassword" placeholder="Password" required>
            <span class="toggle-password" onclick="togglePassword('loginPassword')">👁️</span>
        </div>
        <button type="submit" class="action-btn" onclick="validateLogin()">Login</button>
        <span class="toggle-link" onclick="showSignup()">Don't have an account? Sign Up</span>
    </form>

    <?php
        include 'dbConnect.php';
        if($_SERVER['REQUEST_METHOD'] === "POST")
        {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $checkstmt = $conn->prepare("SELECT * FROM adminsData WHERE email=?");
            $checkstmt->bind_param("s", $email);
            $checkstmt->execute();
            $result = $checkstmt->get_result();
            if($result->num_rows > 0)
            {
                $admin = $result->fetch_assoc();
                if(password_verify($password, $admin['password']))
                {
                    session_start();
                    $_SESSION['adminId'] = $admin['adminId'];
                    $_SESSION['fullName'] = $admin['fullName'];

                    header("Location: adminDashboard.php?login=success");
                    exit;
                }else{
                    echo "<script>alert('Incorrect Email or Password') </script>";
                }
            }else{
                echo "<script>alert('User Not Found!') </script>";
            }

        }
        
    ?>

    <!-- SIGNUP FORM -->
    <form action="registerAdmin.php" method="POST" class="form-box signup-box" id="signupBox">
        <h2>Create an Account 🚀</h2>
        <div class="input-group">
            <input type="text" name="fullName" id="signupName" placeholder="Full Name" required>
        </div>
        <div class="input-group">
            <input type="email" name="email" id="signupEmail" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="number" name="phone" id="signupPhone" placeholder="Phone" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" id="signupPassword" placeholder="Password" required>
            <span class="toggle-password" onclick="togglePassword('signupPassword')">👁️</span>
        </div>
        <div class="input-group">
            <input type="password" name="conPassword" id="confirmPassword" placeholder="Confirm Password" required>
            <span class="toggle-password" onclick="togglePassword('confirmPassword')">👁️</span>
        </div>
        <button type="submit" class="action-btn" onclick="validateSignup()">Sign Up</button>
        <span class="toggle-link" onclick="showLogin()">Already have an account? Login</span>
    </form>
</div>

<script>
    function validateLogin() {
        let email = document.getElementById("loginEmail").value;
        let password = document.getElementById("loginPassword").value;

        if (!email || !password) {
            alert("Please fill in all fields!");
            return;
        }

    }

    function validateSignup() {
        let name = document.getElementById("signupName").value;
        let email = document.getElementById("signupEmail").value;
        let password = document.getElementById("signupPassword").value;
        let confirmPassword = document.getElementById("confirmPassword").value;

        if (!name || !email || !password || !confirmPassword) {
            alert("Please fill in all fields!");
            return;
        }

        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return;
        }
    }

    function togglePassword(inputId) {
        let input = document.getElementById(inputId);
        input.type = input.type === "password" ? "text" : "password";
    }

    function showSignup() {
        document.getElementById("loginBox").style.display = "none";
        document.getElementById("signupBox").style.display = "block";
    }

    function showLogin() {
        document.getElementById("signupBox").style.display = "none";
        document.getElementById("loginBox").style.display = "block";
    }
</script>

</body>
</html>
