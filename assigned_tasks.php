<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}


include_once "database.php";


$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM tasks WHERE assigned_to = ? ORDER BY date DESC";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        :root {
            --primary-color: white;
            --secondary-color: black;
        }
        #head {
            color: var(--secondary-color);
            position: absolute;
            margin-bottom: 350px;
        }
        .tasks {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; 
            margin-top: 100px;
            margin-left: 250px;
        }

        .task {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            margin: 5px;
            background-color: #f9f9f9;
        }
        .task h3 {
            margin-top: 0;
            margin-bottom: 5px;
            color: #333;
        }
        .task p {
            margin: 0;
            color: #666;
        }
        
        .task.waiting {
            background-color: #ffcccc; 
        }
        .task.in-progress {
            background-color: orange;
        }
        .task.completed {
            background-color: #ccffcc; 
        }
        .task-description {
            color: black; 
            font-weight: bold;
            
           
        }
        .toggle-description {
            cursor: pointer;
            font-weight: bold;
            color: black;
        }
            .delete-button {
        background-color: #ff6347; 
        color: white; 
        border: none; 
        padding: 8px 16px; 
        border-radius: 4px; 
        cursor: pointer; 
        transition: background-color 0.3s; 
    }

    .delete-button:hover {
        background-color: #ff483b; 
    }
    .edit-button {
    margin: 3px;
    background-color: #4CAF50; 
    color: white; 
    border: none; 
    padding: 8px 16px; 
    border-radius: 4px; 
    cursor: pointer; 
    transition: background-color 0.3s; 
}

.edit-button:hover {
    background-color: #45a049; 
}
.search-form {
            margin-bottom: 300px;
            margin-left: 400px;
            display: flex;
            align-items: center;
            position:absolute;
            margin-right: 1300px;
        }

        .search-form input[type="text"],
        .search-form select {
            margin-right: 10px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-form button[type="submit"] {
            margin-top: 3px;
            padding: 8px 16px;
            border-radius: 5px;
            border: none;
            background-color: var(--primary-color);
            color: var(--secondary-color);
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .search-form button[type="submit"]:hover {
            background-color: var(--secondary-color);
            color: var(--primary-color);
        }
        .dark-mode input[type="text"]::placeholder {
    color: rgba(255, 255, 255, 0.7);
}
#search{
    color: var(--secondary-color);
    margin-right: 8px;
    font-weight: bold;
}
#search_status option:checked {
    color: white; 
    background-color: var(--primary-color); 
}
#search_status {
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.waiting {
    color: red;
}

.in-progress {
    color: orange;
}

.completed {
    color: green;
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
        <div class="search-form">
        <form action="" method="POST" class="container">
             <a id="search">Search:</a>
             <input type="text" name="search_title" placeholder="Search by title">
        <select name="search_status">
        <option value="">Search by status</option>
        <option value="waiting" class="waiting">Waiting</option>
        <option value="in-progress" class="in-progress">In Progress</option>
        <option value="completed" class="completed">Completed</option>
       </select>
    <button type="submit">Search</button>
       </form>
        </div>
        <h1 id="head">Assigned Tasks</h1>
        <div class="tasks">
        <?php
         if (isset($_POST['search_title']) || isset($_POST['search_status'])) {
            
            $search_title = '%' . $_POST['search_title'] . '%';
            $search_status = $_POST['search_status'];
        
            $sql = "SELECT * FROM tasks WHERE assigned_to = ?";
            $types = "s";
            $params = [$user_id];
        
            if (!empty($_POST['search_title'])) {
                $sql .= " AND title LIKE ?";
                $types .= "s";
                $params[] = &$search_title;
            }
        
            if (!empty($_POST['search_status'])) {
                $sql .= " AND status = ?";
                $types .= "s";
                $params[] = &$search_status;
            }
        
            $sql .= " ORDER BY date DESC";
        
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
            }
        } else {
            
            $sql = "SELECT * FROM tasks WHERE assigned_to = ? ORDER BY date DESC";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
            }
        }

        $xml = new SimpleXMLElement('<tasks/>');
            while ($row = $result->fetch_assoc()) {
                
                $status_class = strtolower(str_replace(' ', '-', $row['status']));
                echo "<div class='task $status_class'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>Status: " . htmlspecialchars($row['status']) . "</p>";
                echo "<p>Date: " . htmlspecialchars($row['date']) . "</p>";
                echo "<p>Assigned To: " . htmlspecialchars($row['assigned_to']) . "</p>";

                echo "<p class='task-description'>" . htmlspecialchars($row['description']) . "</p>";
                echo "<p class='toggle-description' onclick='toggleDescription(this)'>Show Description</p>";
                echo "<button class='delete-button' onclick='deleteTask(" . $row['id'] . ")'>Delete</button>";
                echo "<button class='edit-button' onclick='editTask(" . $row['id'] . ")'>Edit</button>";
                echo "</div>";
                $xml_filename = "xml/tasks.xml";


$xml_string = $xml->asXML();
file_put_contents($xml_filename, $xml_string);
            }
            ?>
        </div>
    </div>
    <script>
   function toggleDescription(element) {
            var description = element.previousSibling;
            if (description.style.display === "none") {
                description.style.display = "block";
                element.textContent = "Hide Description";
            } else {
                description.style.display = "none";
                element.textContent = "Show Description";
            }
        }
    </script>
    <script src="java.js"></script>
    <script>
    function deleteTask(taskId) {
        if (confirm("Are you sure you want to delete this task?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_task.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    window.location.reload(true);
                }
            };
            xhr.send("task_id=" + taskId);
        }
    }
</script>
<script>
    function editTask(taskId) {
        window.location.href = "edit_task.php?task_id=" + taskId;
    }
</script>
</body>
</html> 