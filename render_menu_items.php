<?php
//rendering menu items
include 'mysqli_connect.php';

function renderCards($result) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['basefood_id'];
        $category = '';

        echo '<div class="card" onclick="openModal(' .
            htmlspecialchars(json_encode($row['name'])) . ',' .
            htmlspecialchars(json_encode($row['image_url'])) . ',' .
            htmlspecialchars(json_encode($row['description'] ?? '')) . ',' .
            htmlspecialchars(json_encode($row['price'])) . ',' .
            htmlspecialchars(json_encode($id)) . ',' .
        ')">';
        echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
        echo '<div class="card-content">';
        echo '<div class="card-title">' . htmlspecialchars($row['name']) . '</div>';
        echo '<div class="card-price">₱' . $row['price'] . '</div>';
        echo '</div></div>';
    }
}

$query = "SELECT * FROM base_foods WHERE is_available = 1";
$result = mysqli_query($dbcon, $query);

if ($result && $result->num_rows > 0) {
    renderCards($result);
} else {
    echo "<p>No items found in the base menu.</p>";
}
?>