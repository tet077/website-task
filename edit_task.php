<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["task_id"])) {
    $task_id = $_GET["task_id"];
    
    
    $sql = "SELECT * FROM tasks WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $task_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $task = $result->fetch_assoc();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<style> 
:root {
            --primary-color: white;
            --secondary-color: black;
        }
        #edit{
            color: var(--secondary-color);
            position: absolute;
            margin-bottom: 600px;
        }
    
    form {
        margin-top: 20px;
        width: 50%;
        margin-left: auto;
        margin-right: auto;
    }

    label {
        color: var(--secondary-color);
        font-weight: bold;
    }

    input[type="text"],
    textarea,
    select {
        width: 100%;
        padding: 8px;
        margin-top: 6px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
    #des{
        color: var(--secondary-color);
    }
</style>

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
        <h1 id="edit">Edit Task</h1>
        <form action="update_task.php" method="POST">
    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" value="<?php echo $task['title']; ?>"><br>
    <label for="description">Description:</label><br>
    <textarea id="description" name="description"><?php echo $task['description']; ?></textarea><br>
    <label id="assigned_to_label" for="assigned_to">Assigned to:</label>
    <input type="text" id="assigned_to" name="assigned_to" value="<?php echo $task['assigned_to']; ?>"><br>
    <label for="status">Status:</label><br>
    <select id="status" name="status">
        <option value="Waiting" <?php if ($task['status'] == 'Waiting') echo 'selected'; ?>>Waiting</option>
        <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
        <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
    </select><br><br>
    <input type="submit" value="Update Task">
</form>


</body>
<script src="java.js"></script>
</html>