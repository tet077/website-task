<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";
    
    $user_id = $_SESSION["user_id"];
    
    $sql = "SELECT * FROM user WHERE id = $user_id";
    $result = $mysqli->query($sql);
    
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST["new_username"]) && isset($_POST["new_email"]) && isset($_POST["new_password"]) && isset($_POST["confirm_password"])) {
       
        $new_username = $_POST["new_username"];
        $new_email = $_POST["new_email"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];
        
    
        if ($new_password === $confirm_password) {
            
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            
            $sql = "UPDATE user SET name = '$new_username', email = '$new_email', password_hash = '$hashed_password' WHERE id = $user_id";
            if ($mysqli->query($sql) === TRUE) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . $mysqli->error;
            }
        } else {
            echo "Passwords do not match.";
        }
    } else {
        echo "One or more fields are missing.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
		:root{ --primary-color: white; --secondary-color:black; } 
		.container{ color: var(--secondary-color); 
		}

        .nav-menu ul li{
            display: inline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav class="nav">
            <div class="nav-logo">
                <p>Informatics</p>
            </div>
    
            <div class="nav-menu" id="navMenu">
                <ul>
                <li><a href="index.php" class="link active">Home</a></li>
                    <li><a href="profile.php" class="link active">Profile</a></li>
                    <li><a href="create_list.php" class="link active">Add Tasks</a></li>
                    <li><a href="task.php" class="link active">My Tasks</a></li>
                    <li><a href="assigned_tasks.php" class="link active">Assigned Tasks</a></li>
                </ul>
            </div>
            <div class="nav-button">
                <a class="btn white-btn" id="loginBtn" href="logout.php">Log out</a>
                <img src="moon.png" id="icon">
            </div>
            <div class="nav-menu-btn">
                <i class="bx bx-menu" onclick="myMenuFunction()"></i>
            </div>
        </nav>
        <div class="container">
            <h2>Update Profile</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="new_username">New Username:</label>
                    <input type="text" class="form-control" id="new_username" name="new_username">
                </div>
                <div class="form-group">
                    <label for="new_email">New Email:</label>
                    <input type="email" class="form-control" id="new_email" name="new_email">
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
    <script src="java.js"></script>
</body>
</html>

