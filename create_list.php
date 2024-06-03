<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include_once "database.php";

function sendPushNotification($apiKey, $title, $message, $event) {
    $data = array(
        'key' => $apiKey,
        'title' => $title,
        'msg' => $message,
        'event' => $event
    );
    $data_string = json_encode($data);

    $ch = curl_init('https://api.simplepush.io/send');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
    );

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {
    $title = $_POST["title"];
    $status = $_POST["status"];
    $description = $_POST["description"];
    $assigned_to = $_POST["assigned_to"];
    $user_id = $_SESSION["user_id"];

    // Ανακτούμε το Simple Push κωδικό του χρήστη από τη βάση δεδομένων
    $sql_user = "SELECT simple_push FROM user WHERE id = ?";
    $stmt_user = $mysqli->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $stmt_user->bind_result($simple_push);
    $stmt_user->fetch();
    $stmt_user->close();

    $sql_check = "SELECT COUNT(*) FROM tasks WHERE user_id = ?";
    if ($stmt_check = $mysqli->prepare($sql_check)) {
        $stmt_check->bind_param("i", $user_id);
        $stmt_check->execute();
        $stmt_check->store_result();
        $num_rows = $stmt_check->num_rows;
        $stmt_check->close();

        if ($num_rows >= 1) {
            $sql = "INSERT INTO tasks (user_id, title, status, description, assigned_to, date) VALUES (?, ?, ?, ?, ?, NOW())";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("issss", $user_id, $title, $status, $description, $assigned_to);
                if ($stmt->execute()) {
                    sendPushNotification($simple_push, "New Task Created", "A new task has been created: " . $title, "event");
                    header("Location: create_list.php");
                    exit;
                } else {
                    echo "Κάτι πήγε στραβά. Παρακαλώ προσπαθήστε ξανά.";
                }
                $stmt->close();
            }
        } else {
            $sql = "INSERT INTO tasks (user_id, title, status, description, assigned_to, date) VALUES (?, ?, ?, ?, ?, NOW())";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("issss", $user_id, $title, $status, $description, $assigned_to);
                if ($stmt->execute()) {
                    sendPushNotification($simple_push, "New Task Created", "A new task has been created: " . $title, "event");
                    header("Location: create_list.php");
                    exit;
                } else {
                    echo "Κάτι πήγε στραβά. Παρακαλώ προσπαθήστε ξανά.";
                }
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add task</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
:root{
    --primary-color: white;
    --secondary-color: black;
}
#task{
    position: absolute;
    margin-bottom: 250px;
    color: var(--secondary-color);
}
#title{
    margin-bottom: 250px;
    position: absolute;
    color: var(--secondary-color);
}
#option1{
    color: red;
}
#option2{
    color: orange;
}
#option3{
    color: green;
}
#insert{
    background-color: orange;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
#insert:hover {
    background-color: #ff8c00;
}
#status {
    color: var(--secondary-color);
}
#status option:checked {
    color: white;
}
#status option[value="Σε αναμονή"] {
    color: red;
}
#status option[value="Σε εξέλιξη"] {
    color: orange;
}
#status option[value="Ολοκληρωμένη"] {
    color: greenyellow;
}
#status_label{
    color: var(--secondary-color);
}
#title_1{
    color: var(--secondary-color);
}
#btn{
    background-color: orange;
    cursor: pointer;
}
#description {
    margin-top: 10px;
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: vertical;
}
#des{
    color: var(--secondary-color);
}
#assigned_to_label{
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

        <h1 id="task">Add task</h1>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label id="title_1" for="title">Title:</label>
            <input type="text" name="title" required>
            <label id="status_label" for="status">Status:</label>
            <select id="status_select" name="status">
                <option id="option1" value="Waiting">Waiting</option>
                <option id="option2" value="In progress">In progress</option>
                <option id="option3" value="Completed">Completed</option>
            </select>
            <label id="des" for="description">Description:</label>
            <textarea id="description" name="description" rows="4" cols="50"></textarea>
            <label id="assigned_to_label" for="assigned_to">Assigned to:</label>
            <input type="text" id="assigned_to" name="assigned_to">
            <button id="insert" name="add">Insert</button>
        </form>
    </div>
    <script src="java.js"></script>
</body>
</html>
