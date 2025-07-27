<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        body {
            background-color: #151922;
            color: white;
            font-family: Arial, sans-serif;
        }

        .users-container {
            position: absolute;
            top: 50px;
            left: 270px;
            width: calc(100% - 290px);
        }

        .hero {
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            width: 958px;
            height: 100px;
            text-align: center;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
        }

        .users-table {
            width: 958px;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 5px;
            overflow: hidden;
        }

        .users-table th, .users-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .users-table th {
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
        .role { background-color: blue; }
        
        .action-btn:hover {
            opacity: 0.8;
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
    <div class="users-container">
        <div class="hero">
            <h1>User Management</h1>
        </div>
        <div class="users-content">
            <span class="search">
                <input type="text" id="searchInput" placeholder="Enter User phone">
                <span id="clearBtn" style="display: none;" onclick="clearSearch()">
                    <button class="status-btn" style="color: white;">&times;</button>
                </span>
                <button class="search-btn" onclick="searchUser()">Search</button>

            </span>

            <table class="users-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                     include 'dbConnect.php';
                     $sql = "SELECT * FROM userData";
                     $result = $conn->query($sql);
                     if($result->num_rows>0)
                     {
                        while($row = $result->fetch_assoc())
                        {
                            echo '<tr id="row_' . htmlspecialchars($row['userId']) . '" phone="' . $row['phone'] . '">
                                    <td>' . htmlspecialchars($row['userId']) . '</td>
                                    <td>' . htmlspecialchars($row['fullName']) . '</td>
                                    <td>' . htmlspecialchars($row['email']) . '</td>
                                    <td>' . htmlspecialchars($row['phone']) . '</td>
                                    <td>' . htmlspecialchars($row['userAddress']) . '</td>
                                    <td>
                                        <button class="action-btn delete" onclick="deleteUser(\'' . $row['userId'] . '\')">Delete</button>
                                    </td>
                                </tr>';
                        }
                        

                     }else{
                        echo "<tr><td colspan='7' style='text-align: center;'>No records found</td></tr>";
                     }

                    ?>

                </tbody>
            </table>
        </div>
    </div>

    <script>
function searchUser() {
    let input = document.getElementById("searchInput").value.trim(); // Get user input
    let rows = document.querySelectorAll("tr[phone]"); // Select all table rows with the phone attribute

    if (input === "") {
        alert("Please enter a phone number!");
        return;
    }

    let found = false;

    rows.forEach(row => {
        let userPhone = row.getAttribute("phone"); // Get phone number from the row's phone attribute

        if (userPhone === input) {
            row.style.display = ""; // Show matching row
            found = true;
        } else {
            row.style.display = "none"; // Hide non-matching rows
        }
    });

    // Show "X" button if search input is not empty
    document.getElementById("clearBtn").style.display = input ? "inline" : "none";

    if (!found) {
        alert("User with this phone number not found!");
    }
}

function clearSearch() {
    // Clear the input field
    document.getElementById("searchInput").value = "";
    
    // Show all rows again
    let rows = document.querySelectorAll("tr[phone]");
    rows.forEach(row => {
        row.style.display = ""; // Show all rows
    });

    // Hide the "X" button
    document.getElementById("clearBtn").style.display = "none";
}



    function deleteUser(userId) {
        if (!userId) {
            alert("Error: User ID is undefined!");
            return;
        }

        console.log("Attempting to delete user with ID:", userId); // Debugging

        if (confirm("Are you sure you want to delete this user?")) {
            fetch('deleteUser.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'userId=' + encodeURIComponent(userId)
            })
            .then(response => response.json())
            .then(data => {
                console.log("Server response:", data); // Debugging
                if (data.success) {
                    let row = document.getElementById("row_" + userId);
                    console.log("Trying to remove row with ID:", "row_" + userId, "Found row:", row); // Debugging

                    if (row) {
                        row.remove();
                        alert("User deleted successfully.");
                    } else {
                        alert("Error: Could not find row to remove.");
                    }
                } else {
                    alert("Failed to delete user: " + (data.error || "Unknown error"));
                }
            })
            .catch(error => {
                alert("Error deleting user: " + error);
            });
        }
    }
</script>



</body>
</html>
