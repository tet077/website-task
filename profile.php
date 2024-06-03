<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";
    
    $user_id = $_SESSION["user_id"];
    
    $sql = "SELECT * FROM user WHERE id = $user_id";
    $result = $mysqli->query($sql);
    
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
    <style>
        :root{
            --primary-color: white;
            --secondary-color:black;
        }
        #head{
            color: var(--secondary-color);
            position:absolute;
            margin-bottom: 300px;
        }
        #t{
            color: var(--secondary-color);
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

    <h2 id="head" >Profile</h2>
    
    <table class="table">
        <thead>
            <tr>
                <th id="t">ID</th>
                <th id="t">Username</th>
                <th id="t">Email</th>
                <th id="t">Password</th>
                <th id="t">Actions</th>
            </tr>
        </thead>
        <tbody> 
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td id="t"><?php echo $row['id']; ?></td>
                <td id="t"><?php echo $row['name']; ?></td>
                <td id="t"><?php echo $row['email']; ?></td>
                <td id="t"><?php echo $row['password_hash']; ?></td>
                <td>
                    <a class="btn btn-info" href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
                    &nbsp;
                    <a class="btn btn-danger" href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='5'>No data found</td></tr>";
            }
            ?>                     
        </tbody>
        </table>
        
      <script src="java.js"></script>

</body>
</html>







