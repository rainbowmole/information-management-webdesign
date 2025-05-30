<?php include("mysqli_connect.php"); ?>

<main id="dashboard-section" class="main-section">
    <div class="head-title">
        <div class="left">
            <h1>Dashboard</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="#">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li>
                    <a class="active" href="#">Home</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- for box info ng total orders -->
    <?php
    $q_orders = "SELECT COUNT(order_id) AS total_orders FROM orders WHERE payment_status = 'Pending' and order_status in ('Pending', 'Preparing', 'On the Way')";
    $result_orders = mysqli_query($dbcon, $q_orders);

    $q_sales = "SELECT SUM(total_amount) AS total_sales FROM orders WHERE payment_status = 'Paid' or order_status = 'Delivered'";
    $result_sales = mysqli_query($dbcon, $q_sales);
    ?>
    <ul class="box-info">
        <li>
            <i class='bx bxs-calendar-check' ></i>
            <span class="text">
                <?php
                $fetch_orders = mysqli_fetch_assoc($result_orders);
                $total_orders = htmlspecialchars($fetch_orders["total_orders"]);
                echo "
                <h3>$total_orders</h3>
                <p>New Order</p>
                ";
                ?>
            </span>
        </li>
        
        <li>
            <i class='bx bxs-dollar-circle' ></i>
            <span class="text">
                <?php
                $fetch_sales = mysqli_fetch_assoc($result_sales);
                $total_sales = htmlspecialchars($fetch_sales["total_sales"] !== null ? $fetch_sales["total_sales"] : 0);
                echo "
                <h3>$total_sales</h3>
                <p>Total Sales</p>
                ";
                ?>
            </span>
        </li>
    </ul>

    <?php
    // Query to get orders with user info
    $q = "SELECT o.order_id, o.created_at, o.order_status, u.firstname, u.lastname
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            WHERE o.order_status NOT IN ('Delivered', 'Cancelled')
            ORDER BY o.created_at DESC
            LIMIT 10";  // limit to 10 recent orders

    $result = mysqli_query($dbcon, $q);

    
    $historyq = "SELECT o.order_id, o.created_at, o.order_status, u.firstname, u.lastname
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            WHERE o.order_status IN ('Delivered', 'Cancelled')
            ORDER BY o.created_at DESC
            LIMIT 10";  // limit to 10 recent orders

    $complete_order_result = mysqli_query($dbcon, $historyq);
    ?>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Recent Orders</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Date Order</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Compose user full name
                            $userFullName = htmlspecialchars($row['firstname'] . ' ' . $row['lastname']);

                            // Format date (e.g. 01-10-2021)
                            $orderDate = date('d-m-Y', strtotime($row['created_at']));

                            $status = htmlspecialchars($row['order_status']);
                            $statusClass = strtolower(str_replace(' ', '-', $row['order_status']));

                            echo "<tr>
                                <td>
                                    <img src='img/people.png' alt='/'>
                                    <p>{$userFullName}</p>
                                </td>
                                <td>{$orderDate}</td>
                                <td>
                                    <form method='POST' action='change_status.php'>
                                        <input type='hidden' name='order_id' value='" . htmlspecialchars($row['order_id']) . "'>
                                        <select name='new_status' onchange='this.form.submit()'>";
                                            $statuses = ['Pending','Preparing','On the Way','Delivered','Cancelled'];
                                            foreach ($statuses as $s) {
                                                $selected = $s === $row['order_status'] ? "selected" : "";
                                                $safeStatus = htmlspecialchars($s);
                                                echo "<option value='$safeStatus' $selected>$safeStatus</option>";
                                            }
                                            
                            echo "      </select>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No orders found</td></tr>";
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Completed Orders / Cancelled</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Date Order</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($complete_order_result && $complete_order_result->num_rows > 0) {
                    while ($row = $complete_order_result->fetch_assoc()) {
                        $userFullName = htmlspecialchars($row['firstname'] . ' ' . $row['lastname']);
                        $orderDate = date('d-m-Y', strtotime($row['created_at']));
                        $status = htmlspecialchars($row['order_status']);
                        echo "<tr>
                            <td>
                                <img src='img/people.png' alt='/'><p>{$userFullName}</p>
                            </td>
                            <td>{$orderDate}</td>
                            <td>{$status}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No completed or cancelled orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</main>	