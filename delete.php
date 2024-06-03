<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";
    
    $user_id = $_SESSION["user_id"];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_delete"])) {
   
        $random_email_suffix = rand(1000, 9999);
        $random_password_suffix = rand(1000, 9999);
        
        
        $sql_get_user_info = "SELECT email FROM user WHERE id = $user_id";
        $result = $mysqli->query($sql_get_user_info);
        $row = $result->fetch_assoc();
        $user_email = $row['email'];
        
        $updated_email = $user_email . "_" . $random_email_suffix;
        $updated_password = "deleted_" . $random_password_suffix;
        
       
        $random_username = 'deleted_user_' . rand(1000, 9999);
        
        $sql_update_user_info = "UPDATE user SET name = '$random_username', email = '$updated_email', password_hash = '$updated_password', active = 0 WHERE id = $user_id";
        
        if ($mysqli->query($sql_update_user_info)) {
           
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit;
        } else {
            echo "Error updating record: " . $mysqli->error;
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Delete Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <h2>Delete Account</h2>
            <p>Are you sure you want to delete your account?</p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <button type="submit" class="btn btn-danger" name="confirm_delete">Yes, Delete My Account</button>
                <a href="profile.php" class="btn btn-primary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>

