<?php
session_start();
require 'db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid property ID.');
}

$property_id = intval($_GET['id']);

// Fetch property info
$sql = "SELECT * FROM properties WHERE id=$property_id LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows !== 1) {
    die('Property not found.');
}
$property = $result->fetch_assoc();

// Fetch owner info
$owner_id = intval($property['owner_id']);
$owner_sql = "SELECT name, email FROM users WHERE id=$owner_id LIMIT 1";
$owner_result = $conn->query($owner_sql);
$owner = $owner_result ? $owner_result->fetch_assoc() : null;

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($property['property_name']); ?> - Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .details-card {
            background: white;
            max-width: 540px;
            margin: 38px auto;
            border-radius: 13px;
            box-shadow: 0 6px 32px rgba(22,72,120,0.22);
            padding: 30px 30px 20px 30px;
        }
        .details-card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .details-data h2 { margin-top:0; color:#164878; }
        .owner-block {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 16px 19px;
            margin-top: 19px;
            font-size: 1.04rem;
        }
        .owner-block strong {color:#164878;}
        @media(max-width:600px) {
            .details-card {max-width:98vw; padding:20px 8px 16px 8px;}
            .details-card img {height:170px;}
        }
    </style>
</head>
<body>
    <nav style="position:static;">
        <a href="index.php">Home</a>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php else: ?>
            <a href="list_property.php">List Property</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
        <span style="float:right;font-weight:600;color:#fbc02d; margin-left:22px;">
            <?php if (isset($_SESSION['name'])): ?>
                Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>
            <?php endif; ?>
        </span>
    </nav>
    <div class="details-card">
        <?php
        $images = json_decode($property['images']);
        if (!empty($images) && is_array($images)) {
            echo '<img src="' . htmlspecialchars($images[0]) . '" alt="Property Image">';
        }
        ?>
        <div class="details-data">
            <h2><?php echo htmlspecialchars($property['property_name']); ?></h2>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($property['location']); ?></p>
            <p><strong>Rent:</strong> ₹<?php echo number_format($property['rent']); ?></p>
            <p><strong>Deposit:</strong> ₹<?php echo number_format($property['deposit']); ?></p>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($property['property_type']); ?></p>
            <p><strong>Furnishing:</strong> <?php echo htmlspecialchars($property['furnishing']); ?></p>
            <p><strong>Available from:</strong> <?php echo htmlspecialchars($property['available_from']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($property['description']); ?></p>
        </div>
        <?php if ($owner): ?>
        <div class="owner-block">
            <strong>Owner Info:</strong><br>
            Name: <?php echo htmlspecialchars($owner['name']); ?><br>
            Email: <a href="mailto:<?php echo htmlspecialchars($owner['email']); ?>" ><?php echo htmlspecialchars($owner['email']); ?></a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
