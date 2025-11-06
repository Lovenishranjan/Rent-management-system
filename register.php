<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        die("Please fill all fields.");
    }

    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        die("Email already registered.");
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hash')";
    if ($conn->query($sql) === TRUE) {
        header("Location: login.php?registered=1");
        exit();
    } else {
        die("Error: " . $conn->error);
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
    <h2>Register</h2>
    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
