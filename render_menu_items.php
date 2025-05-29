<?php
// rendering menu items
include 'mysqli_connect.php';

function renderCards($result, $idField) {
    while ($row = $result->fetch_assoc()) {
        // id can be basefood_id, addon_id, or beverage_id
        $id = $row[$idField];
        $category_id = $row['category_id'];

        echo '<div class="card" onclick="openModal(' .
            htmlspecialchars(json_encode($row['name'])) . ',' .
            htmlspecialchars(json_encode($row['image_url'])) . ',' .
            htmlspecialchars(json_encode($row['description'] ?? '')) . ',' .
            htmlspecialchars(json_encode($row['price'])) . ',' .
            htmlspecialchars(json_encode($id)) . ',' .
            htmlspecialchars(json_encode((int)$category_id)) .
        ')">';
        echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
        echo '<div class="card-content">';
        echo '<div class="card-title">' . htmlspecialchars($row['name']) . '</div>';
        echo '<div class="card-price">₱' . $row['price'] . '</div>';
        echo '</div></div>';
    }
}

// Determine what to fetch
$type = $_GET['id'] ?? 'basefood_id';

switch ($type) {
    case 'addon_id':
        $query = "
            SELECT a.addon_id, a.name, a.price, a.image_url, a.category_id
            FROM addons a
            INNER JOIN (
                SELECT name, price, category_id, MIN(addon_id) AS min_id
                FROM addons
                WHERE is_available = 1 AND price > 0
                GROUP BY name, price, category_id
            ) sub ON a.name = sub.name 
                AND a.price = sub.price
                AND a.category_id = sub.category_id
                AND a.addon_id = sub.min_id
            WHERE a.is_available = 1 AND a.price > 0
            ORDER BY a.name;
        ";
        $idField = 'addon_id';
        break;
    case 'beverage_id':
        $query = "SELECT beverage_id, category_id, name, price, image_url FROM beverages WHERE is_available = 1";
        $idField = 'beverage_id';
        break;
    case 'basefood_id':
    default:
        $query = "SELECT basefood_id, category_id, name, description, price, image_url FROM base_foods WHERE is_available = 1";
        $idField = 'basefood_id';
        break;
}

$result = mysqli_query($dbcon, $query);

if ($result && $result->num_rows > 0) {
    renderCards($result, $idField);
} else {
    echo "<p>No items found for this menu.</p>";
}
?>
