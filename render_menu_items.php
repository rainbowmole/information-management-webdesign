<?php
//rendering menu items
include 'mysqli_connect.php';

function renderCards($result, $type) {
    while ($row = $result->fetch_assoc()) {
        // Use only the ID relevant to the item type
        $id = null;
        if ($type === 'basefood_id') {
            $id = $row['basefood_id'];
        } elseif ($type === 'addon_id') {
            $id = $row['addon_id'];
        } elseif ($type === 'beverage_id') {
            $id = $row['beverage_id'];
        } else {
            $id = $row['id'] ?? null; // fallback
        }

        echo '<div class="card" onclick="openModal(' .
            htmlspecialchars(json_encode($row['name'])) . ',' .
            htmlspecialchars(json_encode($row['image_url'])) . ',' .
            htmlspecialchars(json_encode($row['description'] ?? '')) . ',' .
            htmlspecialchars(json_encode($row['price'])) . ',' .
            htmlspecialchars(json_encode($id)) . ',' .
            "'" . ($type === 'basefood_id' ? 'basefood' : ($type === 'beverage_id' ? 'beverage' : 'addon')) . "'" .
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
            SELECT MIN(addon_id) as addon_id, name, price, MIN(image_url) as image_url
            FROM addons
            WHERE is_available = 1 and price > 0
            GROUP BY name, price
            ORDER BY name
        ";
        break;
    case 'beverage_id':
        $query = "SELECT * FROM beverages WHERE is_available = 1";
        break;
    case 'basefood_id':
    default:
        $query = "SELECT * FROM base_foods WHERE is_available = 1";
        break;
}

$result = mysqli_query($dbcon, $query);

if ($result && $result->num_rows > 0) {
    renderCards($result, $type);
} else {
    echo "<p>No items found for this menu.</p>";
}
?>
