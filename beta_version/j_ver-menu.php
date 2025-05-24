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

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>JLougawan - Menu</title>
        <link rel="stylesheet" href="css/menu.css">
        <link rel="stylesheet" href="css/header.css">
    </head>
    <body>
        <?php include 'header.php'; ?>
        <main>
            <h1>Menu</h1>

            <!-- Category Filter -->
            <nav class="menu-nav">
                <button onclick="location.href='menu.php?category=base_foods'">Base Foods</button>
                <button onclick="location.href='menu.php?category=addons'">Add-ons</button>
                <button onclick="location.href='menu.php?category=beverages'">Beverages</button>
            </nav>

            <div class="menu-container" id="menuContainer">
                <?php renderCards($result, $category); ?>
            </div>

            <!-- Modal -->
            <div class="modal" id="itemModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span id="modalTitle">Item Name</span>
                        <span class="close" onclick="closeModal()">❌</span>
                    </div>

                    <div class="modal-body">
                        <img id="modalImage" src="" alt="Item" />
                        <p id="modalDesc">Description here.</p>
                        <p><strong>Price: ₱<span id="modalPrice">0</span></strong></p>

                        <div class="qty">
                            <label style="margin: 0;">Quantity:</label>
                            <button onclick="changeQty(-1)">-</button>
                            <span id="qtyValue">1</span>
                            <button onclick="changeQty(1)">+</button>
                        </div>

                        <hr style="border: none; height: 2px; background-color: #ccc; margin: 20px 0;">
                        <p>Add Ons:</p>
                        <div id="checklist" class="addons">
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button onclick="showCart()">Show Cart</button>
                        <button onclick="addToCart()">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div id="notifContainer" class="notif-container"></div>

            <!-- CART MODAL -->
            <div id="cartModal" class="modal">
                <div class="modal-content cart-content">
                    <div class="modal-header">
                        <h2>
                            <img src="media/icons/bag.png" alt="Cart Icon" style="width: 30px; height: 30px; vertical-align: middle; margin-right: 8px; padding-bottom: 2px;">
                            Your Cart
                        </h2>

                        <span class="close" onclick="closeCart()">❌</span>
                    </div>

                    <div class="modal-body" id="cartItems">
                        <p>Your cart is currently empty.</p>
                    </div>

                    <div class="cart-summary" style="padding: 20px 35px; border-top: 1px solid #eee;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Subtotal</span>
                            <span id="cartSubtotal">₱0.00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Shipping</span>
                            <span>₱50.00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>VAT (12%)</span>
                            <span id="cartVAT">₱0.00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold; margin-top: 10px;">
                            <span>Total</span>
                            <span id="cartTotal">₱0.00</span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button>Checkout</button>
                    </div>
                </div>
            </div>

        </main>

        <script src="js/menu.js"></script>
    </body>
    </html>