<?php


if (empty($_POST["firstname"]) || empty($_POST["lastname"]) || empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["password_confirmation"]) || empty($_POST["simple_push"])) {
    die("All fields are required");
}


if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}


if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}


if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

// Χρησιμοποιούμε password hashing για ασφαλέστερη αποθήκευση του κωδικού
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Σύνδεση στη βάση δεδομένων
$mysqli = require __DIR__ . "/database.php";

// Εισαγωγή δεδομένων στη βάση δεδομένων
$sql = "INSERT INTO user (firstname, lastname, name, email, password_hash, simple_push)
        VALUES (?, ?, ?, ?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}


$simple_push = $_POST["simple_push"];

$stmt->bind_param("ssssss",
                  $_POST["firstname"],
                  $_POST["lastname"],
                  $_POST["name"],
                  $_POST["email"],
                  $password_hash, 
                  $simple_push);
                  
if ($stmt->execute()) {
 
    header("Location: signup-success.html");
    exit;
    
} else {
 
    if ($mysqli->errno === 1062) {
        die("Email already taken");
    } else {
        die("Error: " . $mysqli->error);
    }
}
?>