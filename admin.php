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



























































































































































































































LOADDATA
<?php
$conn = new mysqli('localhost', 'username', 'password', 'assets');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Create table if it does not exist
$query = "
CREATE TABLE IF NOT EXISTS software (
    package VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100),
    version VARCHAR(50),
    description TEXT
) ENGINE=InnoDB;
";
$conn->query($query);

// Insert data with duplicate handling
$insertQuery = "
INSERT INTO software (package, name, version, description)
VALUES
    ('pkg1', 'Software1', '1.0', 'Description1'),
    ('pkg2', 'Software2', '2.0', 'Description2')
ON DUPLICATE KEY UPDATE
    name = VALUES(name), version = VALUES(version), description = VALUES(description);
";
$conn->query($insertQuery);

$conn->close();
?>


SHOWDATA
<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'assets');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch packages for dropdown
$query = "SELECT package FROM software";
$result = $conn->query($query);
$packages = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row['package'];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Data</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Dropdown for selecting package -->
    <label for="packageDropdown">Select Package:</label>
    <select id="packageDropdown">
        <option value="">--Select a Package--</option>
        <?php foreach ($packages as $package): ?>
            <option value="<?= htmlspecialchars($package) ?>"><?= htmlspecialchars($package) ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Placeholder for package details -->
    <div id="packageDetails">
        <h3>Package Details:</h3>
        <table id="detailsTable" border="1" style="display:none;">
            <!-- Dynamically populated content -->
        </table>
    </div>

    <script>
        $('#packageDropdown').on('change', function () {
            const packageId = $(this).val();
            if (packageId) {
                $.ajax({
                    url: 'getPackageData.php',
                    type: 'POST',
                    data: { packageId },
                    success: function (response) {
                        const data = JSON.parse(response);
                        const table = $('#detailsTable');
                        table.empty().show();
                        for (const [key, value] of Object.entries(data)) {
                            table.append(`<tr><td>${key}</td><td>${value}</td></tr>`);
                        }
                    },
                    error: function () {
                        alert('Error fetching package details.');
                    }
                });
            } else {
                $('#detailsTable').hide();
            }
        });
    </script>
</body>
</html>


CHARTTEMP
<?php
// Process form submission and handle temperature conversions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input values
    $startTemp = intval($_POST['startTemp']);
    $endTemp = intval($_POST['endTemp']);
    $increment = intval($_POST['increment']);
    $conversionType = $_POST['conversionType'];
    $chartData = [];

    // Perform temperature conversion
    if ($conversionType === 'CtoF') {
        for ($c = $startTemp; $c <= $endTemp; $c += $increment) {
            $f = ($c * 9 / 5) + 32;
            $chartData[] = ['from' => $c, 'to' => round($f, 2)];
        }
    } elseif ($conversionType === 'FtoC') {
        for ($f = $startTemp; $f <= $endTemp; $f += $increment) {
            $c = ($f - 32) * 5 / 9;
            $chartData[] = ['from' => $f, 'to' => round($c, 2)];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temperature Chart</title>
</head>
<body>
    <?php if (!isset($chartData)) : ?>
        <!-- Input form -->
        <form method="POST" action="tempchart.php">
            <label for="startTemp">Start Temperature:</label>
            <input type="number" id="startTemp" name="startTemp" required>
            <br>
            <label for="endTemp">End Temperature:</label>
            <input type="number" id="endTemp" name="endTemp" required>
            <br>
            <label for="increment">Increment:</label>
            <input type="number" id="increment" name="increment" required>
            <br>
            <label for="conversionType">Conversion Type:</label>
            <select id="conversionType" name="conversionType" required>
                <option value="CtoF">Celsius to Fahrenheit</option>
                <option value="FtoC">Fahrenheit to Celsius</option>
            </select>
            <br><br>
            <button type="submit">Generate Chart</button>
        </form>
    <?php else : ?>
        <!-- Display a link to reset the form -->
        <a href="tempchart.php">Return to Form</a>
        <br><br>
        <!-- Display the temperature chart -->
        <table border="1">
            <thead>
                <tr>
                    <th><?= $conversionType === 'CtoF' ? 'Celsius' : 'Fahrenheit' ?></th>
                    <th><?= $conversionType === 'CtoF' ? 'Fahrenheit' : 'Celsius' ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($chartData as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['from']) ?></td>
                        <td><?= htmlspecialchars($row['to']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>


GET Package
<?php
$conn = new mysqli('localhost', 'username', 'password', 'assets');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageId = $_POST['packageId'];
    $stmt = $conn->prepare("SELECT * FROM software WHERE package = ?");
    $stmt->bind_param('s', $packageId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    echo json_encode($data);
    $stmt->close();
}

$conn->close();
?>




getPackageData
