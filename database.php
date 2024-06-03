<?php

$host = getenv("db_host");
$username = getenv("db_user");
$password = getenv("db_password");
$dbname = "login_db";

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

$mysqlcreateuser = "
    CREATE TABLE if not exists user (
        id int auto_increment,
        name varchar(128),
        Firstname varchar(100),
        Lastname varchar(100),
        email varchar(255),
        active tinyint(1),
        password_hash varchar(255),
        simple_push varchar(10), 
        primary key(id)
    )
";

$result = $mysqli->query($mysqlcreateuser);

$mysqlcreatetask = "
    CREATE TABLE if not exists tasks (
        id int auto_increment,
        date datetime,
        description text,
        title varchar(100),
        status varchar(22),
        user_id int(255),
        assigned_to varchar(100),
        primary key(id)
    )
";

$result = $mysqli->query($mysqlcreatetask);

return $mysqli;
?>


