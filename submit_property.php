<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Please log in to list a property.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $property_name = $conn->real_escape_string($_POST['property_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $property_type = $conn->real_escape_string($_POST['property_type']);
    $furnishing = $conn->real_escape_string($_POST['furnishing']);
    $available_from = $conn->real_escape_string($_POST['available_from']);
    $location = $conn->real_escape_string($_POST['location']);
    $rent = intval($_POST['rent']);
    $deposit = intval($_POST['deposit']);
    $owner_id = $_SESSION['user_id'];

    // Handle image uploads
    $image_paths = [];
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['images']['name'][$key]);
        $target_file = $upload_dir . uniqid() . "_" . $file_name;
        if (move_uploaded_file($tmp_name, $target_file)) {
            $image_paths[] = $target_file;
        }
    }
    $images_json = json_encode($image_paths);

    $stmt = $conn->prepare("INSERT INTO properties
        (property_name, description, property_type, furnishing, available_from, location, rent, deposit, images, owner_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiisi", $property_name, $description, $property_type, $furnishing, $available_from, $location, $rent, $deposit, $images_json, $owner_id);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirect to homepage after property added
        exit();
    } else {
        echo "<script>alert('Error adding property.'); window.location='list_property.php';</script>";
    }
    $stmt->close();
}

$conn->close();
?>
