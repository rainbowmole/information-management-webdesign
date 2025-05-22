    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>JLougawan - Menu</title>
        <link rel="stylesheet" href="css/menu.css">
        <link rel="stylesheet" href="css/header.css">
    </head>
    <body>
        <?php include 'header.php'; ?>
        <main>
            <h1>Menu</h1>
        
            <div class="menu-container">
                
                <div class="card" onclick="openModal('Lugaw w/ Egg', 'media/menu/lugaw-egg.jpg', 'Warm rice porridge with a soft-boiled egg.', 35)">
                    <img src="media/menu/lugaw-egg.jpg" alt="Lugaw w/ Egg" />
                    <div class="card-content">
                        <div class="card-title">Lugaw w/ Egg</div>
                        <div class="card-price">₱35</div>
                    </div>
                </div>

                <div class="card" onclick="openModal('Pares', 'media/menu/beef-pares.jpg', 'Flavorful beef stew served with rice.', 50)">
                    <img src="media/menu/beef-pares.jpg" alt="Beef Pares"/>
                    <div class="card-content">
                        <div class="card-title">Pares</div>
                        <div class="card-price">₱50</div>
                    </div>
                </div>

                <!-- Add more cards here -->
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
                            <input type="checkbox" id="01" name="addons[]" value="Spring Onions (Free)">
                            <label for="01">Add Spring Onions (Free)</label>

                            <input type="checkbox" id="02" name="addons[]" value="Chili Garlic (Free)">
                            <label for="02">Add Chili Garlic (Free)</label>

                            <input type="checkbox" id="03" name="addons[]" value="Garlic chips (₱5)">
                            <label for="03">Add garlic chips (₱5)</label>

                            <input type="checkbox" id="04" name="addons[]" value="Extra egg (₱17)">
                            <label for="04">Add extra egg (₱17)</label>

                            <input type="checkbox" id="05" name="addons[]" value="Tokwa't Baboy (₱35)">
                            <label for="05">Add Tokwa't Baboy (₱35)</label>

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