<?php 
session_start();
require 'db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Browse Properties</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="properties.php">Browse Properties</a>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php else: ?>
            <a href="list_property.php">List Property</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </nav>
    <header>
        <h2>Available Properties</h2>
    </header>
    <div id="properties-list" class="properties-grid">
        <?php
        $sql = "SELECT * FROM properties ORDER BY created_at DESC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $images = json_decode($row['images']);
            echo '<div class="property-card">';
            if (!empty($images)) {
                echo '<img src="' . htmlspecialchars($images[0]) . '" alt="' . htmlspecialchars($row['property_name']) . '">';
            }
            echo '<h3>' . htmlspecialchars($row['property_name']) . '</h3>';
            echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
            echo '<p><strong>Rent:</strong> ₹' . number_format($row['rent']) . '</p>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '</div>';
        }
        $conn->close();
        ?>
    </div>
    <footer>
        <p>© 2025 Rent Management System</p>
    </footer>
</body>
</html>
