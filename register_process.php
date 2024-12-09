<?php

$servername = "localhost";
$db_username = "root";
$db_password = "Password1";
$database = "demo";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new mysqli($servername, $db_username, $db_password, $database);

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $username = $db->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
    $background = $db->real_escape_string($_POST['background']);

    $query = "INSERT INTO users (username, password_hash, background_image) VALUES ('$username', '$password', '$background')";

    if ($db->query($query)) {
        header("Location: login.php");
    } else {
        echo "Error: " . $db->error;
    }

    $db->close();
}
?>
