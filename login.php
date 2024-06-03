<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = sprintf("SELECT * FROM user
                    WHERE email = '%s'",
                   $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
    
    if ($user) {
        
        if (password_verify($_POST["password"], $user["password_hash"])) {
            
            session_start();
            
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user["id"];
            
            header("Location: index.php");
            exit;
        }
    }
    
    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" href="style.css">
    <title>Informatics</title>
</head>
<body>
<div class="wrapper">
  <nav class="nav">
        <div class="nav-logo">
             <p>Informatics</p>
        </div>
    
    <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="login.php" class="link active">Log in</a></li>
                <li><a href="signup.html" class="link active">Sign up</a></li>
                <li><a href="help2.html" class="link">Help</a></li>
            </ul>
    </div>
          <div class="nav-button">
             <img src="moon.png" id="icon">
           </div>
        <div class="nav-menu-btn">
            <i class="bx bx-menu" onclick="myMenuFunction()"></i>
        </div>
  </nav>
    
     <h1 class="login">Login</h1>

    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>

      <form method="post">
     <div class="form1">
         <label for="email">email</label>
         <input type="email" name="email" id="email"
               value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
         <label for="password">Password</label>
         <input type="password" name="password" id="password">
        
        <button>Log in</button>
     </div>
     </form>
    <script src="java.js"></script>
    
</body>
</html>



