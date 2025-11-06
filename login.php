<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];

    $result = $conn->query("SELECT id, password, name FROM users WHERE email='$email'");
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "User not found";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <p style="color:red; text-align:center;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
