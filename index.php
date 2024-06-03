<?php
$mysqli = require __DIR__ . "/database.php";

session_start();

if (isset($_SESSION["user_id"])) {
   
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" href="style.css">
    <style>
@keyframes neonColor {
    0% { color: rgb(255, 0, 0); } 
    25% { color: rgb(0, 255, 0); } 
    50% { color: rgb(0, 0, 255); } 
    75% { color: rgb(255, 255, 0); } 
    100% { color: rgb(255, 0, 255); } 
}

#username {
    font-size: 48px;
    animation: neonColor 5s infinite alternate;
}

@keyframes neonGlow {
    from {
        text-shadow: 0 0 5px rgba(0, 230, 230, 0.8), 0 0 10px rgba(0, 230, 230, 0.8), 0 0 20px rgba(0, 230, 230, 0.8), 0 0 40px rgba(0, 230, 230, 0.8), 0 0 80px rgba(0, 230, 230, 0.8), 0 0 90px rgba(0, 230, 230, 0.8), 0 0 100px rgba(0, 230, 230, 0.8), 0 0 150px rgba(0, 230, 230, 0.8);
    }
    to {
        text-shadow: 0 0 10px rgba(0, 230, 230, 0.8), 0 0 20px rgba(0, 230, 230, 0.8), 0 0 30px rgba(0, 230, 230, 0.8), 0 0 40px rgba(0, 230, 230, 0.8), 0 0 70px rgba(0, 230, 230, 0.8), 0 0 80px rgba(0, 230, 230, 0.8), 0 0 100px rgba(0, 230, 230, 0.8), 0 0 150px rgba(0, 230, 230, 0.8);
    }
}

@keyframes neonFloat {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

    </style>
    <title>Informatics</title>
</head>
<body>
    <?php if (isset($user)): ?>
        <div class="wrapper">
            <nav class="nav">
                <div class="nav-logo">
                    <p>Informatics</p>
                </div>
                <div class="nav-menu" id="navMenu">
                    <ul>
                        <li><a href="index.php" class="link active">Home</a></li>
                        <li><a href="profile.php" class="link active">Profile</a></li>
                        <li><a href="create_list.php" class="link active"> Add Tasks</a></li>
                        <li><a href="task.php" class="link active">My Tasks</a></li>
                        <li><a href="assigned_tasks.php" class="link active">Assigned Tasks</a></li>
                        <li><a href="export_tasks.php" class="link active">Migration Data</a></li>
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
            <p id="username">Welcome <?= htmlspecialchars($user["name"]) ?></p>
        </div>
    <?php else: ?>
        <p id="logout"><a href="login.php">Log in</a> or <a href="signup.html">sign up</a></p>
    <?php endif; ?>
    <script src="java.js"></script>
</body>
</html>