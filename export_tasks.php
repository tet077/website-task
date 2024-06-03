<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied. Please log in first.");
}

$mysqli = require __DIR__ . "/database.php";

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "
    SELECT 
        t.id AS task_id, 
        t.date AS task_date, 
        t.description AS task_description, 
        t.title AS task_title, 
        t.status AS task_status, 
        u1.name AS creator_name, 
        u2.name AS assigned_to_name
    FROM 
        tasks t
    LEFT JOIN 
        user u1 ON t.user_id = u1.id
    LEFT JOIN 
        user u2 ON t.assigned_to = u2.id
    ORDER BY 
        t.id";

$result = $mysqli->query($sql);

if (!$result) {
    die("Error retrieving data: " . $mysqli->error);
}

if ($result->num_rows == 0) {
    die("No tasks found in the database.");
}


$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;


$root = $xml->createElement('TaskMigrationData');
$xml->appendChild($root);


while ($row = $result->fetch_assoc()) {
    $taskElement = $xml->createElement('Task');
    $taskElement->setAttribute('id', $row['task_id']);
    $taskElement->setAttribute('date', $row['task_date']);
    $taskElement->setAttribute('description', $row['task_description']);
    $taskElement->setAttribute('title', $row['task_title']);
    $taskElement->setAttribute('status', $row['task_status']);
    $taskElement->setAttribute('creator_name', $row['creator_name']);
    $taskElement->setAttribute('assigned_to_name', $row['assigned_to_name']);
    $root->appendChild($taskElement);
}


header('Content-Type: text/xml');
header('Content-Disposition: attachment; filename="tasks_export.xml"');

echo $xml->saveXML();


$result->free();
$mysqli->close();


exit();
?>



