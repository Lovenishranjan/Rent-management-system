<?php
session_start();
require 'db_connect.php';

$search = '';
$sql = "SELECT * FROM properties";

if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $search = $conn->real_escape_string(trim($_GET['search']));
    $sql = "SELECT * FROM properties WHERE property_name LIKE '%$search%' OR location LIKE '%$search%'";
}
$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Rent Management System - Home</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        /* your styles here, unchanged */
        body {
            margin: 0;
            background: #f4f4f9;
        }
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            z-index: 100;
            background: #164878;
            color: white;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 8px rgba(0,0,0,0.09);
        }
        .nav-links {
            margin-left: 24px;
            display: flex;
            align-items: center;
            height: 56px;
        }
        .nav-links a {
            margin-right: 24px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 1.00rem;
            transition: color 0.3s;
            line-height: 56px;
        }
        .nav-links a:last-child { margin-right:0; }
        .nav-links a:hover { color: #fbc02d; }
        .nav-right {
            margin-right: 32px;
            font-weight:700;
            color:#fbc02d;
            font-size: 1.08rem;
        }
        .hero-fixed {
            position: fixed;
            top: 40px;
            left: 0;
            width: 100vw;
            background: linear-gradient(120deg, #164878, #1976d2);
            color: white;
            text-align: center;
            padding: 1px 0 13px 0;
            z-index: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .hero-fixed h1 {
            font-size: 2.05rem;
            margin-bottom: 9px;
            margin-top: 0;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .hero-fixed p {
            margin-top: 0;
            margin-bottom: 16px;
            font-size: 1.11rem;
            font-weight: 500;
        }
        .hero-fixed input[type="text"] {
            width: 340px;
            max-width: 92vw;
            padding: 10px 14px;
            font-size: 1.08rem;
            border-radius: 6px;
            border: 1px solid #ddd;
            background: #f4f4f9;
            outline: none;
            transition: box-shadow 0.2s, border 0.2s;
            box-shadow: 0 2px 8px rgba(22,72,120,0.05);
        }
        .content-scroll {
            margin-top: 200px;
            padding: 0 8vw 30px 8vw;
        }
        .properties-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: center;
            max-width: 1100px;
            margin: 0 auto;
        }
        .property-card-link {
            text-decoration: none;
            color: inherit;
        }
        .property-card {
            background: white;
            border-radius: 11px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.10);
            width: 320px;
            min-width:245px;
            min-height: 250px;
            max-width: 95vw;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            margin-bottom: 18px;
            cursor: pointer;
        }
        .property-card:hover {
            transform: translateY(-7px) scale(1.021);
            box-shadow: 0 10px 32px rgba(25,118,210,0.16);
        }
        .property-card img {
            width: 100%;
            height: 190px;
            object-fit: cover;
            border-top-left-radius: 11px;
            border-top-right-radius: 11px;
        }
        .property-card h3 {
            padding: 12px 16px 5px;
            margin: 0;
            color: #164878;
            font-size: 1.15rem;
            font-weight: 650;
        }
        .property-card p {
            padding: 0 16px 4px;
            font-size: 0.98rem;
            line-height: 1.4;
        }
        @media (max-width: 900px) {
            .content-scroll { padding: 0 3vw 30px 3vw;}
            .hero-fixed h1{font-size:1.5rem;}
            .property-card { width: 91vw; max-width:370px;}
        }
        @media (max-width: 600px) {
            nav, .hero-fixed { padding-left: 3px; padding-right: 3px; }
            .nav-links { margin-left: 5px;}
            .nav-right { margin-right: 6px;}
            .hero-fixed input[type="text"] { width:95vw;}
        }
        @media (max-width: 520px) {
            nav { flex-direction: column; align-items: flex-start;}
            .nav-links, .nav-right { margin-left:5px; margin-right: 5px;}
            .hero-fixed{padding:19px 0 13px 0;}
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php else: ?>
                <a href="list_property.php">List Property</a>
                <a href="logout.php">Logout</a>
            <?php endif; ?>
        </div>
        <div class="nav-right">
            <?php if (isset($_SESSION['name'])): ?>
                Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>
            <?php endif; ?>
        </div>
    </nav>
    <div class="hero-fixed">
        <h1>Find Your Perfect Rental Property</h1>
        <p>Search properties by location</p>
        <input type="text" id="searchInput"
            placeholder="Enter city, locality, or landmark" value="<?php echo htmlspecialchars($search); ?>" />
    </div>
    <div class="content-scroll">
        <div id="properties-list" class="properties-grid">
            <?php
            while ($row = $result->fetch_assoc()) {
                $images = json_decode($row['images']);
                echo '<a class="property-card-link" href="property.php?id=' . $row['id'] . '">';
                echo '<div class="property-card">';
                if (!empty($images) && is_array($images)) {
                    echo '<img src="' . htmlspecialchars($images[0]) . '" alt="' . htmlspecialchars($row['property_name']) . '">';
                }
                echo '<h3>' . htmlspecialchars($row['property_name']) . '</h3>';
                echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
                echo '<p><strong>Rent:</strong> ₹' . number_format($row['rent']) . '</p>';
                echo '</div>';
                echo '</a>';
            }
            $conn->close();
            ?>
        </div>
    </div>
    <footer>
        <p>© 2025 Rent Management System</p>
    </footer>
    <script>
        document.getElementById("searchInput")
            .addEventListener("keypress", function (e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    const query = e.target.value.trim();
                    if (query) {
                        window.location.href = "index.php?search=" + encodeURIComponent(query);
                    } else {
                        window.location.href = "index.php";
                    }
                }
            });
    </script>
</body>
</html>
