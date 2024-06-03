<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include_once "database.php";

$user_id = $_SESSION["user_id"];

$sql = "UPDATE tasks 
        INNER JOIN user 
        ON tasks.id = user.id 
        SET tasks.user_id = user.id";

if ($mysqli->query($sql) === TRUE) {
    echo "Η ενημέρωση του πεδίου user_id στον πίνακα tasks ολοκληρώθηκε με επιτυχία.";
} else {
    echo "Σφάλμα κατά την ενημέρωση του πεδίου user_id: " . $mysqli->error;
}

$mysqli->close();
?>
