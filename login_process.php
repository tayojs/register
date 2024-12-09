<?php

session_start();

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
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {
            // Set session variables for the logged-in user
            $_SESSION['user'] = $user['username'];
            $_SESSION['background'] = $user['background_image'];

            // Redirect to the admin page
            header("Location: admin.php");
            exit();
        } else {
            echo "Invalid credentials!";
        }
    } else {
        echo "Invalid credentials!";
    }

    $db->close();
}
?>
