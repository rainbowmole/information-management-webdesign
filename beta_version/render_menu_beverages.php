<?php
include 'mysqli_connect.php';
function renderCards($result, $type = 'base') {
    while ($row = $result->fetch_assoc()) {
        $id = $row['basefood_id'] ?? $row['addon_id'] ?? $row['id']; // adjust column names accordingly
        $name = $row['name'];
        $image = $row['image_url'] ?? ''; // some addons may not have images
        $desc = $row['description'] ?? '';
        $price = $row['price'];

        echo '<div class="card" onclick="openModal(' .
            htmlspecialchars(json_encode($name)) . ',' .
            htmlspecialchars(json_encode($image)) . ',' .
            htmlspecialchars(json_encode($desc)) . ',' .
            htmlspecialchars(json_encode($price)) . ',' .
            htmlspecialchars(json_encode($id)) . ',' .
            htmlspecialchars(json_encode($type)) .
            ')">';
        if ($image) {
            echo '<img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($name) . '">';
        }
        echo '<div class="card-content">';
        echo '<div class="card-title">' . htmlspecialchars($name) . '</div>';
        echo '<div class="card-price">₱' . number_format($price, 2) . '</div>';
        echo '</div></div>';
    }
}

function renderMenuSection($query, $type = 'base') {
    global $dbcon;
    $result = mysqli_query($dbcon, $query);

    if ($result && $result->num_rows > 0) {
        renderCards($result, $type);
    } else {
        echo "<p>No items found for {$type} menu.</p>";
    }
}
?>