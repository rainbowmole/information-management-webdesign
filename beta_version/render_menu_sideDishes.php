<?php
include 'mysqli_connect.php';

$category = $_GET['category'] ?? 'base_foods'; //sets base foods as default page if hindi addons or bevs ang linked

function renderCards($result, $category) {
    while ($row = $result->fetch_assoc()) {
        // Dynamically determine the ID field based on category
        if ($category === 'addons') {
            $id = $row['addon_id'];
        } elseif ($category === 'beverages') {
            $id = $row['beverage_id'];
        } else {
            $id = $row['basefood_id'];
        }

        echo '<div class="card" onclick="openModal(' .
            htmlspecialchars(json_encode($row['name'])) . ',' .
            htmlspecialchars(json_encode($row['image_url'])) . ',' .
            htmlspecialchars(json_encode($row['description'] ?? '')) . ',' .
            htmlspecialchars(json_encode($row['price'])) . ',' .
            htmlspecialchars(json_encode($category)) . ',' .
            htmlspecialchars(json_encode($id)) .
        ')">';

        echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
        echo '<div class="card-content">';
        echo '<div class="card-title">' . htmlspecialchars($row['name']) . '</div>';
        echo '<div class="card-price">₱' . $row['price'] . '</div>';
        echo '</div></div>';
    }
}

switch ($category) {
    case 'addons':
        $query = "SELECT a.addon_id, a.name, a.price, a.image_url FROM addons a JOIN (SELECT MIN(addon_id) AS min_id FROM addons WHERE price != 0.00 GROUP BY name) AS b ON a.addon_id = b.min_id;"; // display lang yung mga items by itself
        break;
    case 'beverages':
        $query = "SELECT * FROM beverages WHERE is_available = 1";
        break;
    default:
        $query = "SELECT * FROM base_foods WHERE is_available = 1";
        break;
}

$result = $dbcon->query($query);
?>