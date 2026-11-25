
<?php
require_once 'settings.php';

$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    die("Database connection failed");
}

$username = "admin";
$password = password_hash("admin", PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO `user` (Username, PasswordHash)
     VALUES (?, ?)"
);

$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "Admin account created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
