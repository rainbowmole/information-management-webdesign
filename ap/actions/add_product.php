<?php
include("../mysqli_connect.php");

// Fetch posted values
$category = $_POST['category'] ?? '';
$basefood_id = $_POST['basefood_id'] ?? null;
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price = $_POST['price'] ?? 0;
$imagePath = '';

// check if valid yung category
$allowedCategories = ['base_foods', 'beverages', 'addons', 'lugaw_addons'];
if (!in_array($category, $allowedCategories)) {
    die("Invalid category.");
}

// Handle image upload
if (!empty($_FILES['image']['name'])) {
    $originalName = basename($_FILES['image']['name']);
    $newFileName = time() . "_" . $originalName;

    // file path/folder structure
    $uploadFolder = __DIR__ . "/../../media/menu/";
    $fullFilePath = $uploadFolder . $newFileName;
    $imagePath = "media/menu/" . $newFileName;

    if (!file_exists($uploadFolder)) {
        mkdir($uploadFolder, 0777, true);
    }

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $fullFilePath)) {
        die("Error: Failed to upload image.");
    }
}

// sql db queries
switch ($category) {
    case 'base_foods':
        $query = "INSERT INTO base_foods (category_id, name, description, price, image_url) VALUES (1, ?, ?, ?, ?)";
        $stmt = $dbcon->prepare($query);
        if (!$stmt) die("Prepare failed: " . $dbcon->error);
        $stmt->bind_param("ssds", $name, $description, $price, $imagePath);
        break;

    case 'beverages':
        $query = "INSERT INTO beverages (category_id, name, price, image_url) VALUES (3, ?, ?, ?)";
        $stmt = $dbcon->prepare($query);
        if (!$stmt) die("Prepare failed: " . $dbcon->error);
        $stmt->bind_param("sds", $name, $price, $imagePath);
        break;

    case 'addons':
        if (!$basefood_id) {
            die("Missing basefood_id for addon.");
        }
        $query = "INSERT INTO addons (category_id, basefood_id, name, price, image_url) VALUES (2, ?, ?, ?, ?)";
        $stmt = $dbcon->prepare($query);
        if (!$stmt) die("Prepare failed: " . $dbcon->error);
        $stmt->bind_param("isds", $basefood_id, $name, $price, $imagePath);
        break;
}

if ($stmt->execute()) {
    header("Location: ../admin.php");
    exit();
} else {
    die("Execute failed: " . $stmt->error);
}
?>
