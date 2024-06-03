<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    // Αν ο χρήστης δεν είναι συνδεδεμένος, επιστρέφουμε μήνυμα λάθους
    echo "You are not logged in.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["task_id"])) {
    // Συμπερίληψη του αρχείου database.php για σύνδεση με τη βάση δεδομένων
    include_once "database.php";

    // Παίρνουμε το task_id από το αίτημα POST
    $task_id = $_POST["task_id"];
    // Παίρνουμε το user_id από τη συνεδρία
    $user_id = $_SESSION["user_id"];

    // Εκτελούμε ένα ερώτημα SQL για να διαγράψουμε το task μόνο εάν ανήκει στον τρέχοντα χρήστη
    $sql_delete = "DELETE FROM tasks WHERE id = ? OR assigned_to = ?";
    if ($stmt_delete = $mysqli->prepare($sql_delete)) {
        // Δέσμευση των παραμέτρων
        $stmt_delete->bind_param("ss", $task_id, $user_id);
        // Εκτέλεση του ερωτήματος
        if ($stmt_delete->execute()) {
            // Επιτυχής διαγραφή
            echo "Task deleted successfully.";
        } else {
            // Αναφορά σφάλματος σε περίπτωση αποτυχίας
            echo "Error deleting task: " . $mysqli->error;
        }
        // Κλείσιμο της δήλωσης
        $stmt_delete->close();
    } else {
        // Αναφορά σφάλματος σε περίπτωση αποτυχίας δημιουργίας της δήλωσης
        echo "Error preparing statement: " . $mysqli->error;
    }
} else {
    // Αν η κλήση δεν είναι POST ή αν δεν περιλαμβάνει το task_id, επιστρέφουμε μήνυμα λάθους
    echo "Invalid request.";
}
?>

