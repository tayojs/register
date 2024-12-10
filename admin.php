<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: warning.php");
    exit();
}

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "Password1";
$database = "demo";

$conn = new mysqli($servername, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Update Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $newUsername = $conn->real_escape_string($_POST['username']);
    $newPassword = $_POST['password'];
    $newBackground = $conn->real_escape_string($_POST['background']);

    // Update query logic
    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password
        $updateQuery = "UPDATE users SET username = '$newUsername', password_hash = '$hashedPassword', background_image = '$newBackground' WHERE id = $id";
    } else {
        $updateQuery = "UPDATE users SET username = '$newUsername', background_image = '$newBackground' WHERE id = $id";
    }

    if ($conn->query($updateQuery)) {
        echo "User updated successfully!";

        // If the logged-in user's details are updated, reflect this in the session
        if ($_SESSION['user'] === $_POST['old_username']) {
            $_SESSION['user'] = $newUsername;
            $_SESSION['background'] = $newBackground;
        }
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle Delete Request
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $deleteQuery = "DELETE FROM users WHERE id = $id";

    if ($conn->query($deleteQuery)) {
        echo "User deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch All Users
$sql = "SELECT id, username, background_image FROM users";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            background-image: url('<?php echo $_SESSION['background']; ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }

        header {
            padding: 10px;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
        }

        table {
            width: 100%;
            margin: 20px auto;
            background: rgba(0, 0, 0, 0.7);
            color: white;
        }

        select {
            color: black;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['user']; ?></h1>
        <p><a href="logout.php" style="color: white;">Logout</a></p>
    </header>

    <h2 style="text-align: center;">Manage Users</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="old_username" value="<?php echo $row['username']; ?>">
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <input type="text" name="username" value="<?php echo $row['username']; ?>" required>
                            </td>
                            <td>
                                <input type="password" name="password" placeholder="New password (optional)">
                                <select name="background">
                                    <option value="images/bg1.jpg" <?php if ($row['background_image'] === 'images/bg1.jpg') echo 'selected'; ?>>Background 1</option>
                                    <option value="images/bg2.jpg" <?php if ($row['background_image'] === 'images/bg2.jpg') echo 'selected'; ?>>Background 2</option>
                                    <option value="images/bg3.jpg" <?php if ($row['background_image'] === 'images/bg3.jpg') echo 'selected'; ?>>Background 3</option>
                                    <option value="images/bg4.jpg" <?php if ($row['background_image'] === 'images/bg4.jpg') echo 'selected'; ?>>Background 4</option>
                                    <option value="images/bg5.jpg" <?php if ($row['background_image'] === 'images/bg5.jpg') echo 'selected'; ?>>Background 5</option>
                                </select>
                                <button type="submit" name="update">Update</button>
                                <button><a href="admin.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a></button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
