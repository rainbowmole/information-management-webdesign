<?php
include("../mysqli_connect.php");

// Sanitize inputs
$id = $_POST['id'] ?? null;
$category = $_POST['category'] ?? null;
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price = $_POST['price'] ?? 0;
$basefood_id = $_POST['basefood_id'] ?? null;

if (!$id || !$category || !$name || !is_numeric($price)) {
    die("Missing required fields.");
}

$allowed = ['base_foods', 'beverages', 'addons'];
if (!in_array($category, $allowed)) {
    die("Invalid category.");
}

// Identify primary key and build query base
$pk = match ($category) {
    'base_foods' => 'basefood_id',
    'beverages' => 'beverage_id',
    'addons' => 'addon_id',
};

// Image handling
$imagePath = '';
if (!empty($_FILES['image']['name'])) {
    $originalName = basename($_FILES['image']['name']);
    $newFileName = time() . "_" . $originalName;

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

// Prepare and run query
switch ($category) {
    case 'base_foods':
        $query = "UPDATE base_foods SET name = ?, description = ?, price = ?" . ($imagePath ? ", image_url = ?" : "") . " WHERE $pk = ?";
        $stmt = $imagePath 
            ? $dbcon->prepare($query)
            : $dbcon->prepare(str_replace(", image_url = ?", "", $query));

        if ($imagePath) {
            $stmt->bind_param("ssdsi", $name, $description, $price, $imagePath, $id);
        } else {
            $stmt->bind_param("ssdi", $name, $description, $price, $id);
        }
        break;

    case 'beverages':
        $query = "UPDATE beverages SET name = ?, price = ?" . ($imagePath ? ", image_url = ?" : "") . " WHERE $pk = ?";
        $stmt = $imagePath 
            ? $dbcon->prepare($query)
            : $dbcon->prepare(str_replace(", image_url = ?", "", $query));

        if ($imagePath) {
            $stmt->bind_param("sdsi", $name, $price, $imagePath, $id);
        } else {
            $stmt->bind_param("sdi", $name, $price, $id);
        }
        break;

    case 'addons':
        if (!$basefood_id) {
            die("Missing basefood_id for addon.");
        }
        $query = "UPDATE addons SET basefood_id = ?, name = ?, price = ?" . ($imagePath ? ", image_url = ?" : "") . " WHERE $pk = ?";
        $stmt = $imagePath 
            ? $dbcon->prepare($query)
            : $dbcon->prepare(str_replace(", image_url = ?", "", $query));

        if ($imagePath) {
            $stmt->bind_param("isdsi", $basefood_id, $name, $price, $imagePath, $id);
        } else {
            $stmt->bind_param("isdi", $basefood_id, $name, $price, $id);
        }
        break;
}

if ($stmt->execute()) {
    header("Location: /Kalye-Co./ap/admin.php");
    exit();
} else {
    die("Update failed: " . $stmt->error);
}
?>
