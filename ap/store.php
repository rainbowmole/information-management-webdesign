<?php include("mysqli_connect.php"); ?>

<main id="store-section" class="main-section" style="display: none;">
    <div class="head-title">
        <div class="left" style="width: 100%;">
            <h1 style="text-align: center;">My Store</h1>
            <div class="store-nav">
                <button class="store-tab active" onclick="showCategory('base')">Base Foods</button>
                <button class="store-tab" onclick="showCategory('beverage')">Beverages</button>
                <button class="store-tab" onclick="showCategory('addon')">Add Ons</button>
                <button class="store-tab" onclick="showCategory('add')">Add Product</button>
            </div>
        </div>
    </div>

    <div id="base" class="table-data category-section" style="margin-top: 0;">
        <div class="table-data" style="padding-top:0;">
            <div class="order">
                <h2 style="text-align: center; margin-bottom: 24px; color: #fc921b;">Base Foods</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th><th>Price</th><th>Available</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "SELECT * FROM base_foods";
                    $result = $dbcon->query($query);
                    while($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>₱<?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['is_available'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <a href="edit_product_form.php?id=<?= $row['basefood_id'] ?>">Edit</a> |
                            <a href="#" onclick="event.preventDefault(); openDeleteModal('<?= $row['basefood_id'] ?>', 'base_foods')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="beverage" class="table-data category-section" style="display: none;">
        <div class="table-data" style="padding-top:0;">
            <div class="order">
                <h2 style="text-align: center; margin-bottom: 24px; color: #fc921b;">Beverages</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th><th>Price</th><th>Available</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "SELECT * FROM beverages";
                    $result = $dbcon->query($query);
                    while($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>₱<?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['is_available'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <a href="edit_product_form.php?id=<?= $row['beverage_id'] ?>">Edit</a> |
                            <a href="#" onclick="event.preventDefault(); openDeleteModal('<?= $row['beverage_id'] ?>', 'beverages')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
                    
    <div id="addon" class="table-data category-section" style="display: none;">
        <div class="table-data" style="padding-top:0;">
            <div class="order">
                <h2 style="text-align: center; margin-bottom: 24px; color: #fc921b;">Add Ons</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th><th>Price</th><th>Available</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "SELECT * FROM addons";
                    $result = $dbcon->query($query);
                    while($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>₱<?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['is_available'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <a href="edit_product_form.php?id=<?= $row['addon_id'] ?>">Edit</a> |
                            <a href="#" onclick="event.preventDefault(); openDeleteModal('<?= $row['addon_id'] ?>', 'addons')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="add" class="table-data category-section" style="display: none;">
        <div class="table-data" style="padding-top:0;">
            <div class="order">
                <h2 style="text-align: center; margin-bottom: 24px; color: #fc921b;">Add Product</h2>
                <form action="store.php" method="post" enctype="multipart/form-data" class="product-form">

                    <div class="form-group">
                        <label for="category_select">Category</label>
                        <select name="category" id="category_select" required>
                            <option value="">-- Select Category --</option>
                            <option value="base_foods">Base Food</option>
                            <option value="beverages">Beverage</option>
                            <option value="addons">Add-on</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name_input">Name</label>
                        <input type="text" id="name_input" name="name" required>
                    </div>

                    <div class="form-group" id="description_wrapper">
                        <label for="description_field">Description</label>
                        <textarea name="description" id="description_field" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price_input">Price</label>
                        <div class="price-wrapper">
                            <button type="button" class="price-btn" id="decrease_price">–</button>
                            <input type="number" step="0.01" id="price_input" name="price" required min="0" value="0.00">
                            <button type="button" class="price-btn" id="increase_price">+</button>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="image_input">Image</label>
                        <div class="custom-file-wrapper">
                            <input type="file" id="image_input" name="image" hidden>
                            <button type="button" class="custom-file-btn" id="image_trigger">Choose File</button>
                            <span id="file_name">No file chosen</span>
                        </div>
                    </div>

                    <div class="form-group" id="addon_type_wrapper" style="display: none;">
                        <label for="basefood_id_select">Add-on for</label>
                        <select name="basefood_id" id="basefood_id_select">
                            <option value="">-- Select Base Food --</option>
                            <?php
                            $q = "SELECT basefood_id, name FROM base_foods WHERE is_available = 1 ORDER BY name ASC";
                            $result = mysqli_query($dbcon, $q);

                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id = (int)$row['basefood_id'];
                                    $name = htmlspecialchars($row['name']);
                                    echo "<option value=\"$id\">$name</option>";
                                }
                            } else {
                                echo '<option value="">Error loading options</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-download">Add Product</button>
                </form>
            </div>
        </div>
    </div>


</main>

<!-- Delete Confirmation Modal -->
<!-- <div id="deleteModal" class="modal">
    <div style="background:#fff; padding:20px; text-align:center; min-width: 300px;">
        <h3>Are you sure you want to delete this item?</h3>
        <form method="POST" action="actions/delete_product.php">
            <input type="hidden" name="id" id="deleteId">
            <input type="hidden" name="table" id="deleteTable">
            <button type="submit">Yes, Delete</button>
            <button type="button" onclick="closeDeleteModal()">Cancel</button>
        </form>
    </div>
</div> -->
