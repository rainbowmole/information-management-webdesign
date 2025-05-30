<?php
include("mysqli_connect.php");

$id = $_GET['id'] ?? null;
$category = $_GET['category'] ?? null;

if (!$id || !$category) {
    die("Missing ID or category.");
}

$allowed = ['base_foods', 'beverages', 'addons'];
if (!in_array($category, $allowed)) {
    die("Invalid category.");
}

// Fetch item
$pk = match ($category) {
    'base_foods' => 'basefood_id',
    'beverages' => 'beverage_id',
    'addons' => 'addon_id',
};

$query = "SELECT * FROM $category WHERE $pk = ?";
$stmt = $dbcon->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
?>

<h2>Edit Product</h2>

<form action="actions/update_product.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">

    <label>Name: <input type="text" name="name" required value="<?= htmlspecialchars($product['name']) ?>"></label><br>

    <?php if ($category === 'base_foods'): ?>
        <label>Description:<br>
            <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
        </label><br>
    <?php endif; ?>

    <label>Price: <input type="number" name="price" step="0.01" required value="<?= $product['price'] ?>"></label><br>

    <?php if ($category === 'addons'): ?>
        <label>Add-on for:
            <select name="basefood_id" required>
                <option value="">-- Select Base Food --</option>
                <?php
                $q = "SELECT basefood_id, name FROM base_foods WHERE is_available = 1";
                $res = $dbcon->query($q);
                while ($row = $res->fetch_assoc()):
                    $selected = ($product['basefood_id'] == $row['basefood_id']) ? 'selected' : '';
                ?>
                    <option value="<?= $row['basefood_id'] ?>" <?= $selected ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label><br>
    <?php endif; ?>

    <label>Image: <input type="file" name="image"></label><br>
    <?php if (!empty($product['image_url'])): ?>
        <p>Current image: <img src="<?= $product['image_url'] ?>" alt="Image" width="100"></p>
    <?php endif; ?>

    <button type="submit">Update Product</button>
</form>

