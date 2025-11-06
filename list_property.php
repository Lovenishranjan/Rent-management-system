<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>List Your Property | Rent Management System</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            z-index: 100;
            background: #164878;
            color: white;
            height: 36px;
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
        main {margin-top:60px;}
        form {
            margin-top:40px;
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
    <main>
        <header>
            <h2 style="text-align:center;">List Your Property</h2>
            <p style="text-align:center;">Fill the form below to add your property to our listing platform.</p>
        </header>
        <form id="listPropertyForm" action="submit_property.php" method="POST" enctype="multipart/form-data">
            <label for="property_name">Property Name:</label>
            <input type="text" id="property_name" name="property_name" required />
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="5" required></textarea>
            <label for="property_type">Property Type:</label>
            <select id="property_type" name="property_type" required>
                <option value="">Select Type</option>
                <option value="apartment">Apartment</option>
                <option value="villa">Villa</option>
                <option value="studio">Studio</option>
                <option value="office">Office Space</option>
            </select>
            <label for="furnishing">Furnishing:</label>
            <select id="furnishing" name="furnishing" required>
                <option value="">Select Furnishing</option>
                <option value="furnished">Furnished</option>
                <option value="semi-furnished">Semi-Furnished</option>
                <option value="unfurnished">Unfurnished</option>
            </select>
            <label for="available_from">Available From:</label>
            <input type="date" id="available_from" name="available_from" required />
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required />
            <label for="monthly_rent">Monthly Rent (₹):</label>
            <input type="number" id="monthly_rent" name="rent" required min="0" />
            <label for="security_deposit">Security Deposit (₹):</label>
            <input type="number" id="security_deposit" name="deposit" required min="0" />
            <label for="images">Property Images:</label>
            <input type="file" id="images" name="images[]" accept="image/*" multiple required />
            <button type="submit">Submit Property</button>
        </form>
    </main>
    <footer>
        <p>© 2025 Rent Management System</p>
    </footer>
</body>
</html>
