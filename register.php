<?php
$servername = "localhost";
$username = "root";
$password = "Password1";
$database = "demo";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $background = $_POST['background'];

    // Hash the password before saving
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, password_hash, background_image) VALUES ('$username', '$hashedPassword', '$background')";

    if ($conn->query($query)) {
        echo "User registered successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        #preview {
            width: 200px;
            height: 150px;
            background-size: cover;
            margin-top: 10px;
        }
    </style>
    <script>
		document.addEventListener('DOMContentLoaded', () => {
			const dropdown = document.getElementById('background');
			const preview = document.getElementById('preview');

			// Set the initial background to the first option
			const firstImage = dropdown.options[0].value;
			preview.style.backgroundImage = 'url(' + firstImage + ')';

			// Listen for dropdown changes
			dropdown.addEventListener('change', function () {
				const selectedImage = this.value;
				preview.style.backgroundImage = 'url(' + selectedImage + ')';
			});
		});
	</script>

</head>
<body>
    <h1>Register</h1>
    <form method="POST" action="register.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="background">Select Background:</label>
        <select id="background" name="background" onchange="previewBackground(this.value)">
            <option value="images/bg1.jpg">Background 1</option>
            <option value="images/bg2.jpg">Background 2</option>
            <option value="images/bg3.jpg">Background 3</option>
            <option value="images/bg4.jpg">Background 4</option>
            <option value="images/bg5.jpg">Background 5</option>
        </select><br><br>

        <div id="preview"></div><br><br>

        <button type="submit" name="register">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
