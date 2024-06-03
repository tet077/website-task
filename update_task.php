<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["task_id"])) {
    $task_id = $_POST["task_id"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $status = $_POST["status"];
    $assigned_to = $_POST["assigned_to"];

    
    $sql = "UPDATE tasks SET title = ?, description = ?, status = ?, assigned_to = ? WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssssi", $title, $description, $status, $assigned_to, $task_id);
        if ($stmt->execute()) {
           
            header("Location: task.php");
            exit;
        } else {
            
            echo "Σφάλμα: Αδυναμία ενημέρωσης της εργασίας.";
        }
        $stmt->close();
    }
}
?>

