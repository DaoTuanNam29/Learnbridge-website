
<?php
session_start();
require_once 'settings.php';

// Connect to database
$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    die("Database connection failed.");
}

$error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Find user in database
    $stmt = $conn->prepare(
        "SELECT UserID, Username, PasswordHash
         FROM user
         WHERE Username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        // Verify password
        if (password_verify($password, $row['PasswordHash'])) {

            // Create a new session ID
            session_regenerate_id(true);

            // Save login information
            $_SESSION['user_id'] = $row['UserID'];
            $_SESSION['username'] = $row['Username'];

            // Redirect to manager page
            header("Location: manage.php");
            exit();

        } else {
            $error = "Invalid username or password.";
        }

    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Manager Login</title>
</head>
<body>

    <h1>HR Manager Login</h1>

    <?php if ($error != ""): ?>
        <p style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="login.php">

        <label for="username">Username:</label>
        <input type="text" id="username"
               name="username" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password"
               name="password" required>

        <br><br>

        <button type="submit">Login</button>

    </form>

</body>
</html>
